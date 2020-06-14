<?php
/**
 * Created by PhpStorm.
 * User: Jigulskiy.Vladislav
 * Date: 01.10.2019
 * Time: 15:40
 */

use Symfony\Component\Yaml\Yaml;

class DBase
{
    private $lastQuery = '';
    private $queueQuery = [];
    private static $_instance = null;
    private $db; // Ресурс работы с БД

    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }


    public function Connect($user, $password, $base, $host = 'localhost', $port = 3306)
    {
        $this->db = @mysqli_connect($host, $user, $password, $base, $port );
        $this->db->set_charset("utf8");
    }


    private function dbDie()
    {
        return die(mysqli_connect_error());
    }
    /**
     *
     */
    public function close()
    {
        @mysqli_close($this->db);
    }

    /**
     * @param $query
     * @param bool $nores
     * @return array|bool
     */
    public function query($query, $nores = false)
    {
        $this->lastQuery = $query;
        $this->queueQuery[] = $query;
        $result = @mysqli_query($this->db, $query);
        if ($result === false) {
            return false;
        }
        if ($nores) {
            return true;
        }
        $ret_array = array();
        while ($res = mysqli_fetch_assoc($result)) {
            $ret_array[] = $res;
        }
        mysqli_free_result($result);
        return $ret_array;
    }

    /**
     * @return int
     */
    public function lastInsertId(): int
    {
        return (int)mysqli_insert_id($this->db);
    }

    /**
     * @return string
     */
    public function lastError()
    {
        return mysqli_error($this->db);
    }

    /**
     * @param string $str
     * @return string
     */
    public function escape($str = '')
    {
        return mysqli_real_escape_string($this->db, $str);
    }

    /**
     * @return string
     */
    public function getLastQuery(): string
    {
        return $this->lastQuery;
    }

    /**
     * @return array
     */
    public function getQueueQuery(): array
    {
        return $this->queueQuery;
    }

    /**
     *
     */
    public function begin()
    {
        mysqli_begin_transaction($this->db);
    }

    /**
     *
     */
    public function commit()
    {
        mysqli_commit($this->db);
    }

    /**
     *
     */
    public function rollback()
    {
        mysqli_rollback($this->db);
    }


    /**
     * @param $table
     * @param $map
     * @param $id
     * @return array
     */
    private function __getID($table, $map, $id): array
    {
        $result = [];
        $q = "SELECT " . $map['id'] . " as id FROM " . $table . " WHERE " . $map['id'] . "='" . $id . "'";
        $res = $this->query($q);
        if ($res === false) {
            return [false, [4]];
        }
        $mapRev = [];
        foreach ($map as $k => $v) {
            $mapRev[$v] = $k;
        }
        for ($i = 0, $c = count($res); $i < $c; $i++) {
            $result[$i] = [];
            foreach ($res[$i] as $k => $v) {
                if (!empty($this->map[$k])) {
                    $result[$i][$k] = $v;
                }
            }
        }
        return [true, $result];
    }


    /**
     * @param $table
     * @param $id
     * @param $param
     * @param $map
     * @return array
     */
    public function upsert($table, $id, $param, $map, $autoInc = true): array
    {
        $new = false;
        $q = '';
        if ($id > 0) {
            $res = $this->__getID($table, $map, $id);
            if (!$res[0]) {
                return $res;
            }
            if (count($res[1]) > 0) {
                $update = [];
                foreach ($map as $k => $v) {
                    if ($k == 'id') {
                        continue;
                    }
                    if (isset($param[$k])) {
                        $update[] = $v . "='" . $param[$k] . "'";
                    }
                }
                if (!empty($update)) {
                    $q = "update " . $table . " SET " . implode(',', $update) . " WHERE id=" . $id;
                }
            } else {
                $id = 0;
            }
        }
        if ($id == 0) {
            $insert = [];
            $values = [];
            foreach ($map as $k => $v) {
                if ($k == 'id' && $autoInc) {
                    continue;
                }
                if (isset($param[$k])) {
                    $insert[] = $v;
                    $values[] = $param[$k];
                }
            }
            if (!empty($insert)) {
                $q = "INSERT INTO " . $table .
                    " (" . implode(',', $insert) . ") VALUES ('" . implode("','", $values) . "')";
            }
        }
        if ($q == '') {
            return [false, [3]];
        }
        $r = $this->query($q, true);
        if ($r === false) {
            return [false, [4], $q];
        }
        if ($id == 0) {
            $id = $this->lastInsertId();
            $new = true;
            //send data to Horev
        }
        return [true, $id, $new];
    }

    /**
     * @param array $select
     * @param array $filter
     * @param array $map
     * @param array $hasTest
     * @return array
     */
    public function selectValue($select = [], $filter = [], $map = [], $hasTest = [], $table = ''): array
    {
        if ($table!='')
            $table=str_replace('.','',$table).'.';
        $result = [];
        $has = [];
        $hasProp = [];
        foreach ($hasTest as $k => $v) {
            $has[$v]=true;
        }
        if (!empty($select)) {
            foreach ($hasTest as $k => $v) {
                $has[$v]=false;
            }
            if (!in_array('id', $select)) {
                $select[] = array_search('id',$map);
            }
            foreach ($map as $k => $v) {
                if (in_array($k, $select)) {
                    $result[] = $table.$v . ' as ' . $k;
                }
            }
            foreach ($hasTest as $k => $v) {
                $has[$v]=in_array($v, $select) || isset($select[$v]);
                if (isset($select[$v])) {
                    $hasProp[$v]=$select[$v];
                }
            }
        }
        if (empty($result)) {
            foreach ($map as $k => $v) {
                $result[] = $table.$v . ' as ' . $k;
            }
        }
        $result = empty($result) ? '*' : implode(',', $result);
        $where = $this->createFilter($filter, $map, $table);
        $where = trim($where) == '' ? '' : (' WHERE ' . $where);

        return [
            'select'=>$result,
            'where'=>$where,
            'has'=>$has,
            'hasProp'=>$hasProp,
            'join'=>''
        ];
    }

    public function innerJoin($selectValue, $T1, $T2, $l, $r, $map_t1, $select = [], $filter = [], $was = false)
    {
        $sql = $this->selectValue($select, $filter, $map_t1, [], $T1, true);
        if (!empty($sql['select'])) {
            if ($sql['select'] == '*') {
                $sql['select'] = '';
                foreach ($map_t1 as $k => $v) {
                    $sql['select'] = "$T1.$v as $T1" . '_' . "$k";
                }
            }
            $selectValue['select'] .= ', ' . $sql['select'];
        }
        if (!empty($sql['where'])) {
            $selectValue['where'] .= ', ' . $sql['where'];
        }
        if ($was) {
            $selectValue['join'] .= " INNER JOIN $T1 on $T1.$l = $T2.$r";
        } else {
            $selectValue['join'] .= " INNER JOIN $T1 on $T1.$l = $T2.$r";
        }
        return $selectValue;
    }

    /**
     * @param array $filter
     * @param array $map
     * @return string
     */
    public function createFilter($filter = [], $map = [], $table='')
    {
        if ($table!='')
            $table=str_replace('.','',$table).'.';
        $where='';
        if (!empty($filter)) {
            $logic = 'AND';
            $w = [];
            foreach ($filter as $k => $v) {
                if ($k === 'logic') {
                    $logic = $v;
                } else {
                    if (is_numeric($k) && is_array($v)) {
                        $tmp = $this->createFilter($v, $map, $table);
                        if (!empty($tmp)) {
                            $w[] = $tmp;
                        }
                    } else {
                        $eq=[
                            substr($k, 0, 1),
                            substr($k, 1, 1)
                        ];
                        $k=preg_replace('/^[%!&$><=]+/', '', $k);
                        if (!empty($map[$k])) {
                            if ($eq[0]==="&" && is_array($v)) {
                                foreach ($v as $v_i => $v_v) {
                                    $v[$v_i]=$this->escape($v_v);
                                }
                                $w[] = $table.$map[$k] . " IN ('" . implode("','", $v) . "')";
                            } elseif ($eq[0]==="$" && is_array($v)) {
                                foreach ($v as $v_i => $v_v) {
                                    $v[$v_i]=$this->escape($v_v);
                                }
                                $w[] = $table.$map[$k] . " NOT IN ('" . implode("','", $v) . "')";
                            } elseif ($eq[0]==="%") {
                                $w[] = $table.$map[$k] . " LIKE '%" . $this->escape($v) . "%'";
                            } elseif ($eq[0]==="!") {
                                $w[] = $table.'.'.$map[$k] . " != '" . $this->escape($v) . "'";
                            } elseif ($eq[0]==="<") {
                                if ($eq[1]==="=") {
                                    $w[] = $table.$map[$k] . " <= '" . $this->escape($v) . "'";
                                } else {
                                    $w[] = $table.$map[$k] . " < '" . $this->escape($v) . "'";
                                }
                            } elseif ($eq[0]===">") {
                                if ($eq[1]==="=") {
                                    $w[] = $table.$map[$k] . " >= '" . $this->escape($v) . "'";
                                } else {
                                    $w[] = $table.$map[$k] . " > '" . $this->escape($v) . "'";
                                }
                            } else {
                                $w[] = $table.$map[$k] . " = '" . $this->escape($v) . "'";
                            }
                        }
                    }
                }
            }
            $where = empty($w) ? '' : ' (' . implode(' ' . $logic . ' ', $w) . ') ';
        }
        return $where;
    }
}

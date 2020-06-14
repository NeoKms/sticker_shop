-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Апр 09 2020 г., 07:09
-- Версия сервера: 5.7.24-log
-- Версия PHP: 7.2.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `geekbrains`
--

-- --------------------------------------------------------

--
-- Структура таблицы `basket`
--

CREATE TABLE `basket` (
  `id` int(11) NOT NULL,
  `id_good` int(11) NOT NULL,
  `id_order` int(11) NOT NULL,
  `count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `basket`
--

INSERT INTO `basket` (`id`, `id_good`, `id_order`, `count`) VALUES
(99, 1, 185, 1),
(100, 2, 185, 1),
(101, 3, 186, 1),
(102, 2, 186, 2),
(103, 7, 188, 1),
(104, 5, 188, 1),
(105, 4, 188, 1),
(106, 6, 188, 3),
(107, 16, 190, 13),
(108, 16, 187, 7);

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `active` tinyint(1) DEFAULT '0',
  `code` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `name`, `parent_id`, `active`, `code`) VALUES
(1, 'Стикеры с киану', NULL, 1, 'stickers'),
(2, 'Мемы', NULL, 1, 'memes');

-- --------------------------------------------------------

--
-- Структура таблицы `goods`
--

CREATE TABLE `goods` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `price` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `category_id` int(11) NOT NULL,
  `special` tinyint(1) NOT NULL DEFAULT '0',
  `picture` text NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `goods`
--

INSERT INTO `goods` (`id`, `name`, `price`, `active`, `category_id`, `special`, `picture`, `description`) VALUES
(1, 'Киану \"Почему?\"', 200, 1, 1, 1, 'upload/goods/stickers/why.png', 'Lorem ipsum eu massa morbi commodo vivamus, inceptos nec class pellentesque ante volutpat donec, aptent curabitur ultricies morbi quis arcu pretium sociosqu euismod hendrerit iaculis dolor ultrices lacus placerat morbi torquent euismod, senectus etiam rhoncus ultrices ac consequat mi magna nulla curabitur faucibus elit, vestibulum pellentesque nulla semper suspendisse commodo at gravida rhoncus vehicula litora.'),
(2, 'Киану на мотоцикле', 400, 1, 1, 1, 'upload/goods/stickers/motoKeany.png', 'Mi at nibh purus aliquam mollis nibh proin scelerisque magna et, netus rhoncus per nulla metus odio venenatis vehicula feugiat, velit rutrum ullamcorper convallis fringilla lobortis iaculis vel feugiat ipsum nec pellentesque aptent metus suscipit orci duis vestibulum varius, eu praesent blandit tellus tempus ante arcu proin, purus quam platea vel eget donec integer curabitur.'),
(3, 'Киану грустный', 300, 1, 1, 1, 'upload/goods/stickers/badkeany.png', 'Quam donec erat etiam interdum tristique venenatis hac adipiscing enim vehicula dictumst sodales euismod dolor aenean, metus convallis placerat senectus lobortis nibh sociosqu aliquet arcu lectus aliquam velit dictum himenaeos eros viverra sapien etiam sed suscipit nunc commodo senectus, dictumst cubilia netus fusce curabitur fringilla velit augue nulla mauris hendrerit egestas.'),
(4, 'Киану курящий', 250, 1, 1, 1, 'upload/goods/stickers/smoke_keany.png', 'Pulvinar tempus rhoncus ultrices elementum dictumst aliquam vehicula curabitur scelerisque, dapibus maecenas erat hac tempor scelerisque netus etiam, sollicitudin lorem posuere pellentesque nullam viverra curabitur tellus sit eros imperdiet bibendum enim phasellus ultricies quisque iaculis quisque consequat, placerat vitae eros donec vehicula velit malesuada placerat neque, conubia nunc risus nec eros sapien erat fames lacus.'),
(5, 'Киану фейспалмит', 600, 1, 1, 1, 'upload/goods/stickers/facepalm_keany.png', 'Integer mattis senectus libero vitae molestie convallis quisque magna, suscipit vehicula accumsan dui morbi ad nam, ac fermentum himenaeos lacus vulputate praesent curabitur cursus tortor posuere volutpat ad vehicula morbi dui rutrum id, vehicula nisi justo sollicitudin dictum venenatis tempor nunc, primis fermentum donec cras malesuada mi diam in dolor tempor dui varius consequat sed lectus ipsum erat placerat, ornare morbi habitasse mattis nostra netus odio.'),
(6, 'Киану молится', 200, 1, 1, 0, 'upload/goods/stickers/pray_keany.png', 'Tempor posuere placerat tristique aptent tincidunt curabitur, leo platea arcu curabitur duis lorem, himenaeos non elit consequat metus elementum suspendisse risus ad aliquet id aliquam quisque diam libero potenti platea nostra ipsum dapibus, volutpat ac semper tempus integer quisque taciti sociosqu euismod laoreet justo sociosqu conubia phasellus tempus fusce lacinia lacus, iaculis dapibus ipsum nec praesent senectus, vel suspendisse litora odio.'),
(7, 'Мем про мем', 255, 1, 2, 0, 'upload/goods/memes/cover_6.jpg', 'Tempus egestas vivamus urna ultricies ultrices suspendisse consectetur adipiscing convallis, vitae sapien curabitur platea per viverra leo mauris, metus turpis enim interdum praesent diam sit cubilia congue ut duis sit egestas maecenas nullam tristique lectus enim dui, iaculis ac fusce et nulla vivamus integer himenaeos lorem, id pulvinar scelerisque bibendum lobortis hac egestas senectus volutpat.'),
(9, 'Киану смущенный', 200, 1, 1, 0, 'upload\\goods\\stickers\\keanu2.png', 'Parturient montes, nascetur ridiculus mus. Cras ligula urna, dignissim auctor tellus fringilla, dignissim semper metus. Quisque volutpat lacus et odio venenatis semper. Nulla ac sollicitudin justo. Fusce consectetur, tellus non tristique sodales, urna urna dapibus ligula, sit amet gravida mi tortor pretium purus. Suspendisse potenti. Nulla vel dictum justo. Curabitur feugiat nisl nec ornare pulvinar. Phasellus eget dolor pretium, sollicitudin est ac, imperdiet justo. Donec rutrum sapien ex, vitae malesuada sapien ultrices vitae. Etiam ultrices pretium tincidunt. Vivamus vestibulum nulla quis malesuada ultricies.'),
(10, 'Киану грубый', 1200, 1, 1, 0, 'upload\\goods\\stickers\\keany3.png', 'Ulum eget sagittis nunc. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Cras ligula urna, dignissim auctor tellus fringilla, dignissim semper metus. Quisque volutpat lacus et odio venenatis semper. Nulla ac sollicitudin justo. Fusce consectetur, tellus non tristique sodales, urna urna dapibus ligula, sit amet gravida mi tortor pretium purus. Suspendisse potenti. Nulla vel dictum justo. Curabitur feugiat nisl nec ornare pulvinar. Phasellus eget dolor pretium, sollicitudin est ac, imperdiet justo. Donec rutrum sapien ex, vitae malesuada sapien ultrices vitae. Etiam ultrices pretium tincidunt. Vivamus vestibulum nulla quis malesuada ultricies.'),
(11, 'Киану молодой', 60, 0, 1, 0, 'upload\\goods\\stickers\\keany4.jpg', 'Vestibulum eget sagittis nunc. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Cras ligula urna, dignissim auctor tellus fringilla, dignissim semper metus. Quisque volutpat lacus et odio venenatis semper. Nulla ac sollicitudin justo. Fusce consectetur, tellus non tristique sodales, urna urna dapibus ligula, sit amet gravida mi tortor pretium purus. Suspendisse potenti. Nulla vel dictum justo. Curabitur feugiat nisl nec ornare pulvinar. Phasellus eget dolor pretium, sollicitudin est ac, imperdiet justo. Donec rutrum sapien ex, vitae malesuada sapien ultrices vitae. Etiam ultrices pretium tincidunt. Vivamus vestibulum nulla quis malesuada ultricies.'),
(12, 'Киану великолепный', 500, 0, 1, 1, 'upload\\goods\\stickers\\keany5.jpg', 'Tis ipsum. Vestibulum eget sagittis nunc. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Cras ligula urna, dignissim auctor tellus fringilla, dignissim semper metus. Quisque volutpat lacus et odio venenatis semper. Nulla ac sollicitudin justo. Fusce consectetur, tellus non tristique sodales, urna urna dapibus ligula, sit amet gravida mi tortor pretium purus. Suspendisse potenti. Nulla vel dictum justo. Curabitur feugiat nisl nec ornare pulvinar. Phasellus eget dolor pretium, sollicitudin est ac, imperdiet justo. Donec rutrum sapien ex, vitae malesuada sapien ultrices vitae. Etiam ultrices pretium tincidunt. Vivamus vestibulum nulla quis malesuada ultricies.'),
(13, 'Киану радостный', 340, 1, 1, 0, 'upload\\goods\\stickers\\keany6.png', 'Ert erat, non venenatis ipsum. Vestibulum eget sagittis nunc. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Cras ligula urna, dignissim auctor tellus fringilla, dignissim semper metus. Quisque volutpat lacus et odio venenatis semper. Nulla ac sollicitudin justo. Fusce consectetur, tellus non tristique sodales, urna urna dapibus ligula, sit amet gravida mi tortor pretium purus. Suspendisse potenti. Nulla vel dictum justo. Curabitur feugiat nisl nec ornare pulvinar. Phasellus eget dolor pretium, sollicitudin est ac, imperdiet justo. Donec rutrum sapien ex, vitae malesuada sapien ultrices vitae. Etiam ultrices pretium tincidunt. Vivamus vestibulum nulla quis malesuada ultricies.'),
(14, 'Киану встревоженный', 550, 1, 1, 0, 'upload\\goods\\stickers\\keany7.png', 'Nenatis ipsum. Vestibulum eget sagittis nunc. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Cras ligula urna, dignissim auctor tellus fringilla, dignissim semper metus. Quisque volutpat lacus et odio venenatis semper. Nulla ac sollicitudin justo. Fusce consectetur, tellus non tristique sodales, urna urna dapibus ligula, sit amet gravida mi tortor pretium purus. Suspendisse potenti. Nulla vel dictum justo. Curabitur feugiat nisl nec ornare pulvinar. Phasellus eget dolor pretium, sollicitudin est ac, imperdiet justo. Donec rutrum sapien ex, vitae malesuada sapien ultrices vitae. Etiam ultrices pretium tincidunt. Vivamus vestibulum nulla quis malesuada ultricies.'),
(15, 'Киану любящий', 330, 1, 1, 0, 'upload\\goods\\stickers\\keany8.png', 'Uet erat, non venenatis ipsum. Vestibulum eget sagittis nunc. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Cras ligula urna, dignissim auctor tellus fringilla, dignissim semper metus. Quisque volutpat lacus et odio venenatis semper. Nulla ac sollicitudin justo. Fusce consectetur, tellus non tristique sodales, urna urna dapibus ligula, sit amet gravida mi tortor pretium purus. Suspendisse potenti. Nulla vel dictum justo. Curabitur feugiat nisl nec ornare pulvinar. Phasellus eget dolor pretium, sollicitudin est ac, imperdiet justo. Donec rutrum sapien ex, vitae malesuada sapien ultrices vitae. Etiam ultrices pretium tincidunt. Vivamus vestibulum nulla quis malesuada ultricies.'),
(16, 'Киану божественный', 1, 1, 1, 1, 'upload\\goods\\stickers\\keany9.png', 'Non venenatis ipsum. Vestibulum eget sagittis nunc. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Cras ligula urna, dignissim auctor tellus fringilla, dignissim semper metus. Quisque volutpat lacus et odio venenatis semper. Nulla ac sollicitudin justo. Fusce consectetur, tellus non tristique sodales, urna urna dapibus ligula, sit amet gravida mi tortor pretium purus. Suspendisse potenti. Nulla vel dictum justo. Curabitur feugiat nisl nec ornare pulvinar. Phasellus eget dolor pretium, sollicitudin est ac, imperdiet justo. Donec rutrum sapien ex, vitae malesuada sapien ultrices vitae. Etiam ultrices pretium tincidunt. Vivamus vestibulum nulla quis malesuada ultricies.'),
(17, 'Киану суровый', 400, 1, 1, 0, 'upload\\goods\\stickers\\keany10.png', 'Bi eu aliquet erat, non venenatis ipsum. Vestibulum eget sagittis nunc. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Cras ligula urna, dignissim auctor tellus fringilla, dignissim semper metus. Quisque volutpat lacus et odio venenatis semper. Nulla ac sollicitudin justo. Fusce consectetur, tellus non tristique sodales, urna urna dapibus ligula, sit amet gravida mi tortor pretium purus. Suspendisse potenti. Nulla vel dictum justo. Curabitur feugiat nisl nec ornare pulvinar. Phasellus eget dolor pretium, sollicitudin est ac, imperdiet justo. Donec rutrum sapien ex, vitae malesuada sapien ultrices vitae. Etiam ultrices pretium tincidunt. Vivamus vestibulum nulla quis malesuada ultricies.'),
(18, 'Киану рокер', 200, 1, 1, 0, 'upload\\goods\\stickers\\keany11.png', 'Orbi eu aliquet erat, non venenatis ipsum. Vestibulum eget sagittis nunc. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Cras ligula urna, dignissim auctor tellus fringilla, dignissim semper metus. Quisque volutpat lacus et odio venenatis semper. Nulla ac sollicitudin justo. Fusce consectetur, tellus non tristique sodales, urna urna dapibus ligula, sit amet gravida mi tortor pretium purus. Suspendisse potenti. Nulla vel dictum justo. Curabitur feugiat nisl nec ornare pulvinar. Phasellus eget dolor pretium, sollicitudin est ac, imperdiet justo. Donec rutrum sapien ex, vitae malesuada sapien ultrices vitae. Etiam ultrices pretium tincidunt. Vivamus vestibulum nulla quis malesuada ultricies.'),
(19, 'Киану страдающий', 9999, 1, 1, 0, 'upload\\goods\\stickers\\keany12.png', 'Iquet erat, non venenatis ipsum. Vestibulum eget sagittis nunc. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Cras ligula urna, dignissim auctor tellus fringilla, dignissim semper metus. Quisque volutpat lacus et odio venenatis semper. Nulla ac sollicitudin justo. Fusce consectetur, tellus non tristique sodales, urna urna dapibus ligula, sit amet gravida mi tortor pretium purus. Suspendisse potenti. Nulla vel dictum justo. Curabitur feugiat nisl nec ornare pulvinar. Phasellus eget dolor pretium, sollicitudin est ac, imperdiet justo. Donec rutrum sapien ex, vitae malesuada sapien ultrices vitae. Etiam ultrices pretium tincidunt. Vivamus vestibulum nulla quis malesuada ultricies.'),
(20, 'Киану задумчивый', 300, 1, 1, 0, 'upload\\goods\\stickers\\keany13.png', 'Aliquet erat, non venenatis ipsum. Vestibulum eget sagittis nunc. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Cras ligula urna, dignissim auctor tellus fringilla, dignissim semper metus. Quisque volutpat lacus et odio venenatis semper. Nulla ac sollicitudin justo. Fusce consectetur, tellus non tristique sodales, urna urna dapibus ligula, sit amet gravida mi tortor pretium purus. Suspendisse potenti. Nulla vel dictum justo. Curabitur feugiat nisl nec ornare pulvinar. Phasellus eget dolor pretium, sollicitudin est ac, imperdiet justo. Donec rutrum sapien ex, vitae malesuada sapien ultrices vitae. Etiam ultrices pretium tincidunt. Vivamus vestibulum nulla quis malesuada ultricies.'),
(21, 'Киану привлекает внимание', 650, 1, 1, 0, 'upload\\goods\\stickers\\keany14.png', 'Rat, non venenatis ipsum. Vestibulum eget sagittis nunc. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Cras ligula urna, dignissim auctor tellus fringilla, dignissim semper metus. Quisque volutpat lacus et odio venenatis semper. Nulla ac sollicitudin justo. Fusce consectetur, tellus non tristique sodales, urna urna dapibus ligula, sit amet gravida mi tortor pretium purus. Suspendisse potenti. Nulla vel dictum justo. Curabitur feugiat nisl nec ornare pulvinar. Phasellus eget dolor pretium, sollicitudin est ac, imperdiet justo. Donec rutrum sapien ex, vitae malesuada sapien ultrices vitae. Etiam ultrices pretium tincidunt. Vivamus vestibulum nulla quis malesuada ultricies.'),
(22, 'Киану кибернетический', 1500, 1, 1, 0, 'upload\\goods\\stickers\\keany16.png', 'Eu aliquet erat, non venenatis ipsum. Vestibulum eget sagittis nunc. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Cras ligula urna, dignissim auctor tellus fringilla, dignissim semper metus. Quisque volutpat lacus et odio venenatis semper. Nulla ac sollicitudin justo. Fusce consectetur, tellus non tristique sodales, urna urna dapibus ligula, sit amet gravida mi tortor pretium purus. Suspendisse potenti. Nulla vel dictum justo. Curabitur feugiat nisl nec ornare pulvinar. Phasellus eget dolor pretium, sollicitudin est ac, imperdiet justo. Donec rutrum sapien ex, vitae malesuada sapien ultrices vitae. Etiam ultrices pretium tincidunt. Vivamus vestibulum nulla quis malesuada ultricies.');

-- --------------------------------------------------------

--
-- Структура таблицы `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `groups`
--

INSERT INTO `groups` (`id`, `name`) VALUES
(1, 'Администраторы'),
(2, 'Пользователи');

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `code` text NOT NULL,
  `date` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1 - открытый. 2 - закрытый'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `id_user`, `code`, `date`, `status`) VALUES
(185, 58, '1586366212.3571', '2020-04-08 20:16:52', 2),
(186, 58, '1586366248.9962', '2020-04-08 20:17:28', 2),
(187, 58, '1586366302.9277', '2020-04-08 20:18:22', 2),
(188, 59, '1586366308.0504', '2020-04-08 20:18:28', 2),
(189, 59, '1586366329.8043', '2020-04-08 20:18:49', 1),
(190, 60, '1586366332.5127', '2020-04-08 20:18:52', 2),
(191, 60, '1586366362.1791', '2020-04-08 20:19:22', 1),
(192, 54, '1586366404.0557', '2020-04-08 20:20:04', 1),
(193, NULL, '1586379141.3529', '2020-04-08 23:52:21', 1),
(194, NULL, '1586379525.1634', '2020-04-08 23:58:45', 1),
(195, NULL, '1586379545.8255', '2020-04-08 23:59:05', 1),
(196, NULL, '1586380287.9967', '2020-04-09 00:11:27', 1),
(197, NULL, '1586380298.5517', '2020-04-09 00:11:38', 1),
(198, 58, '1586381023.3651', '2020-04-09 00:23:43', 1),
(199, NULL, '1586381042.9976', '2020-04-09 00:24:02', 1),
(200, NULL, '1586411789.194', '2020-04-09 08:56:29', 1),
(201, NULL, '1586411870.8405', '2020-04-09 08:57:50', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `pages`
--

CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `good_id` int(11) NOT NULL,
  `basket_code` text,
  `datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `pages`
--

INSERT INTO `pages` (`id`, `user_id`, `good_id`, `basket_code`, `datetime`) VALUES
(36, NULL, 1, '1586207244.0772', '2020-04-07 00:07:24'),
(37, NULL, 1, '1586207436.13', '2020-04-07 00:10:36'),
(38, NULL, 1, '1586207514.503', '2020-04-07 00:11:54'),
(39, NULL, 1, '1586238557.5426', '2020-04-07 08:49:17'),
(40, NULL, 1, '1586239201.9737', '2020-04-07 09:00:01'),
(41, NULL, 1, '1586239365.2354', '2020-04-07 09:02:45'),
(42, NULL, 1, '1586239429.4993', '2020-04-07 09:03:49'),
(43, NULL, 1, '1586239532.8923', '2020-04-07 09:05:32'),
(44, NULL, 1, '1586239689.0964', '2020-04-07 09:08:09'),
(45, NULL, 1, '1586239774.1877', '2020-04-07 09:09:34'),
(46, NULL, 1, '1586239790.9419', '2020-04-07 09:09:50'),
(47, NULL, 1, '1586239821.2641', '2020-04-07 09:10:21'),
(48, NULL, 1, '1586239867.4976', '2020-04-07 09:11:07'),
(49, 36, 2, '1586239821.2641', '2020-04-07 19:17:57'),
(50, NULL, 1, '1586276296.0682', '2020-04-07 19:18:16'),
(51, NULL, 1, '1586277092.4372', '2020-04-07 19:31:32'),
(52, NULL, 2, '1586277092.4372', '2020-04-07 20:05:24'),
(53, NULL, 3, '1586277092.4372', '2020-04-07 20:25:37'),
(54, NULL, 21, '1586277092.4372', '2020-04-07 20:48:51'),
(55, NULL, 1, '1586289436.0423', '2020-04-07 22:57:16'),
(56, 38, 2, '1586293038.9524', '2020-04-08 08:42:55'),
(57, 38, 19, '1586293038.9524', '2020-04-08 08:43:02'),
(58, 38, 15, '1586293038.9524', '2020-04-08 08:43:05'),
(59, 38, 18, '1586293038.9524', '2020-04-08 08:43:07'),
(60, 38, 17, '1586293038.9524', '2020-04-08 08:43:09'),
(61, 38, 14, '1586293038.9524', '2020-04-08 08:43:11'),
(62, 38, 20, '1586293038.9524', '2020-04-08 08:43:13'),
(63, 38, 6, '1586293038.9524', '2020-04-08 08:43:23'),
(64, 38, 22, '1586293038.9524', '2020-04-08 08:43:26'),
(65, 38, 21, '1586293038.9524', '2020-04-08 08:43:28'),
(66, 38, 16, '1586293038.9524', '2020-04-08 08:43:55'),
(67, NULL, 1, '1586325104.008', '2020-04-08 08:51:44'),
(68, NULL, 1, '1586325123.1349', '2020-04-08 08:52:03'),
(69, NULL, 1, '1586325139.1691', '2020-04-08 08:52:19'),
(70, NULL, 1, '1586325346.833', '2020-04-08 08:55:46'),
(71, NULL, 1, '1586328429.298', '2020-04-08 09:47:27'),
(72, 54, 2, '1586361447.7389', '2020-04-08 19:00:41'),
(73, 54, 1, '1586362170.7947', '2020-04-08 19:25:36'),
(74, NULL, 1, '1586364027.4167', '2020-04-08 19:40:27'),
(75, NULL, 1, '1586364085.6982', '2020-04-08 19:41:25'),
(76, NULL, 1, '1586365448.4214', '2020-04-08 20:04:08'),
(77, NULL, 1, '1586365501.7906', '2020-04-08 20:05:01'),
(78, NULL, 1, '1586365531.7845', '2020-04-08 20:05:31'),
(79, NULL, 1, '1586365945.6175', '2020-04-08 20:12:25'),
(80, NULL, 1, '1586366212.3571', '2020-04-08 20:16:52'),
(81, NULL, 1, '1586366308.0504', '2020-04-08 20:18:28'),
(82, NULL, 1, '1586366332.5127', '2020-04-08 20:18:52'),
(83, NULL, 1, '1586366404.0557', '2020-04-08 20:20:04'),
(84, NULL, 1, '1586379141.3529', '2020-04-08 23:52:21'),
(85, NULL, 1, '1586379525.1634', '2020-04-08 23:58:45'),
(86, NULL, 1, '1586379545.8255', '2020-04-08 23:59:05'),
(87, NULL, 1, '1586380287.9967', '2020-04-09 00:11:28'),
(88, NULL, 1, '1586380298.5517', '2020-04-09 00:11:38'),
(89, NULL, 1, '1586381042.9976', '2020-04-09 00:24:03'),
(90, NULL, 1, '1586411789.194', '2020-04-09 08:56:29'),
(91, NULL, 1, '1586411870.8405', '2020-04-09 08:57:50');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` text NOT NULL,
  `password` text NOT NULL,
  `email` text,
  `phone` text,
  `name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `email`, `phone`, `name`) VALUES
(54, 'root', 'e50229f2a2a16d6f083c68ae7ccbd6687234679fd5ebce8d4f96582ec2963aad', 'test@gmail.com', '111111111', 'Владислав'),
(58, '1111111111', 'e50229f2a2a16d6f083c68ae7ccbd6687234679fd5ebce8d4f96582ec2963aad', 'test@gmail.com', '1111111111', 'Саша'),
(59, '2222222222', 'e50229f2a2a16d6f083c68ae7ccbd6687234679fd5ebce8d4f96582ec2963aad', 'test@gmail.com', '2222222222', 'Матвей'),
(60, '3333333333', 'e50229f2a2a16d6f083c68ae7ccbd6687234679fd5ebce8d4f96582ec2963aad', 'test@gmail.com', '3333333333', 'Киану');

-- --------------------------------------------------------

--
-- Структура таблицы `user_group`
--

CREATE TABLE `user_group` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user_group`
--

INSERT INTO `user_group` (`id`, `user_id`, `group_id`) VALUES
(13, 54, 1),
(14, 55, 2),
(15, 56, 2),
(16, 57, 2),
(17, 58, 2),
(18, 59, 2),
(19, 60, 2);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `basket`
--
ALTER TABLE `basket`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_good` (`id_good`),
  ADD KEY `id_order` (`id_order`);

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Индексы таблицы `goods`
--
ALTER TABLE `goods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Индексы таблицы `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `status` (`status`);

--
-- Индексы таблицы `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `good_id` (`good_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user_group`
--
ALTER TABLE `user_group`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `group_id` (`group_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `basket`
--
ALTER TABLE `basket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `goods`
--
ALTER TABLE `goods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT для таблицы `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=202;

--
-- AUTO_INCREMENT для таблицы `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT для таблицы `user_group`
--
ALTER TABLE `user_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

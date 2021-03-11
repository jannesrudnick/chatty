SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `messages` (
  `mid` int(11) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sender` int(11) NOT NULL,
  `subject` varchar(50) NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `messages` (`mid`, `timestamp`, `sender`, `subject`, `message`) VALUES
(1, '2021-03-11 21:43:56', 1, 'Hey John!', 'How are u doin?'),
(2, '2021-03-11 21:44:39', 2, 'Hey!', 'I\'m fine, thx\r\n\r\nbest regards');

CREATE TABLE `read` (
  `uid` int(11) NOT NULL,
  `mid` int(11) NOT NULL,
  `seen` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `read` (`uid`, `mid`, `seen`) VALUES
(1, 2, 1),
(2, 1, 0);

CREATE TABLE `user` (
  `uid` int(11) NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `firstname` varchar(20) NOT NULL,
  `lastname` varchar(20) NOT NULL,
  `username` varchar(6) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `user` (`uid`, `gender`, `firstname`, `lastname`, `username`, `password`) VALUES
(1, 'male', 'John', 'Doe', 'johdoe', '098f6bcd4621d373cade4e832627b4f6'),
(2, 'male', 'John', 'Smith', 'johsmi', '098f6bcd4621d373cade4e832627b4f6');


ALTER TABLE `messages`
  ADD PRIMARY KEY (`mid`);

ALTER TABLE `read`
  ADD PRIMARY KEY (`uid`,`mid`);

ALTER TABLE `user`
  ADD PRIMARY KEY (`uid`);


ALTER TABLE `messages`
  MODIFY `mid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `user`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

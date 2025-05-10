# Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
#
# MIT 라이선스 하에 제공됩니다.
# 전체 저작권 및 라이선스 정보는 LICENSE.txt 파일에서 확인할 수 있습니다.
# 파일을 재배포할 경우 위 저작권 표시를 유지해야 합니다.
# MIT 라이선스 (https://opensource.org/licenses/mit-license.php)

CREATE TABLE `sessions` (
  `id` char(40) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `created` datetime DEFAULT CURRENT_TIMESTAMP, -- 선택 사항, MySQL 5.6.5+ 필요
  `modified` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- 선택 사항, MySQL 5.6.5+ 필요
  `data` blob DEFAULT NULL,  -- PostgreSQL에서는 blob 대신 bytea 사용
  `expires` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

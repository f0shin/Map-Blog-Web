# Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
#
# MIT 라이선스 하에 제공됩니다.
# 전체 저작권 및 라이선스 정보는 LICENSE.txt 파일에서 확인할 수 있습니다.
# 파일을 재배포할 경우 위 저작권 표시를 유지해야 합니다.
# MIT 라이선스 (https://opensource.org/licenses/mit-license.php)

CREATE TABLE i18n (
    id int NOT NULL auto_increment,
    locale varchar(6) NOT NULL,
    model varchar(255) NOT NULL,
    foreign_key int(10) NOT NULL,
    field varchar(255) NOT NULL,
    content text,
    PRIMARY KEY (id),
    UNIQUE INDEX I18N_LOCALE_FIELD(locale, model, foreign_key, field),
    INDEX I18N_FIELD(model, foreign_key, field)
);

# GuestBook
Гостевая книга на PHP

Представляет собой простой проект гостевой книги с возможностью добавления и удаления отзывов.
Интерфейс добавления отзывов, и их модерации совмещён (а для их разделения между собой добавлена имитация админки).
Фактически, имеем лишь две рабочие страницы - это страница списка отзывов, и страница отзыва.

Для установки необходим web-сервер с подключенным php и mysql (данные для подключения mysql лежат в vars.php; по умолчанию это localhost, admin/admin).

Для работы должна иметься mysql-база с именем tst. В ней должна быть единственная таблица с именем reviews:
- reviews:
	- ID :: int primary autoinc
	- title  :: varchar(256)
	- email :: varchar(256)
	- text :: varchar(3000)
	- accepted :: bit


При переходе на index.php мы видим список отзывов. Поначалу он будет пустой (если не удалось подключиться к БД - будет сообщение об этом).

Т.к. у нас имитация админки, переключение между админом и юзером идёт по button'у вверху справа (запись идёт в cookies).

Добавлять отзывы может только юзер. Для добавления переключимся на юзера и нажмём "Добавить". Заполним Название, E-mail, Текст; пройдём капчу; нажмём "Добавить". Отзыв должен уйти на модерацию (это значит, что юзер его не видит, пока админ его не одобрит).

Переключаемся на интерфейс админа, видим появившийся отзыв. Мы можем его одобрить, либо отклонить уже одобренный отзыв, если надо. Если мы его одобрим - он станет видимым для юзера. Переходим по ссылке в таблице на отзыв, жмём на "Одобрить". Отзыв одобрен. Теперь он будет виден и юзеру.


UPD 2019.03.18 ::

При переходе на новую версию MySQL (8.0.4) может появиться ошибка:
	mysqli::__construct(): The server requested authentication method unknown to the client [caching_sha2_password]

Для её устранения надо прописать в конфигурационном файле MySQL ( по умолчанию c:\ProgramData\MySQL\MySQL Server 8.0\my.ini ) переключение с нового плагина аутентификации caching_sha2_password на старый mysql_native_password.
[mysqld]
default-authentication-plugin=mysql_native_password

Также надо убедиться, что есть доступ к Интернету не через прокси (капча использует функцию file_get_contents(), которая в проекте не настроена на работу через прокси).

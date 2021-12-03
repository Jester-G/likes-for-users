# Отчет для отображения лайков пользователей
<h3>Установка</h3>
<ol>
  <li>Скачать архив или клонировать проект через git;</li>
  <li>Создать пустую базу данных и выполнить бэкап с помощью файла thank_db.sql для добавления таблиц и данных;</li>
  <li>В файле, по пути config/Database.php, добавить свои данные для соединения с базой данных;</li>
  <li>Перейти в директорию public и запустить php server, используя команду php -S localhost:8080;</li>
  <li>Выбрать нужный отчет, кликнув по кнопке.</li>
</ol>
<hr>
<h3>Общая информация</h3>
<ol><b>Доступно два отчета:</b>
  <li>Отображает количество отправленных лайков;</li>
  <li>Отображает количество полученных лайков.</li>
</ol>
<ol><b>Список фильтров:</b>
  <li>Сортировка по дате (от/до);</li>
  <li>Сортировка по департаменту;</li>
  <li>Сортировка по дате и департаменту.</li>
</ol>
<p>Отчет всегда выводится с соритровкой по количеству лайков (от большего к меньшему).</p>
<p>В отчете реализована пагинация (20 записей на страницу).</p>
<p>Для отображения календаря используется <a href="https://github.com/dangrossman/daterangepicker">скрипт</a>.</p>

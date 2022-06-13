<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Abz.agancy testing task

- [**Task result on the host**](https://ingvar-abz2.pp.ua/)

  (https://ingvar-abz2.pp.ua/)


- [PHP backend task](https://drive.google.com/file/d/1zUC2D72mGqSip5-3zvKJYGMsd7-KEfHG/view).
- [API Documentation](https://apidocs.abz.dev/test_assignment_for_frontend_developer_api_documentation#).

### Requirements:

* [x] Разверните Вашу работу на любом хостинге (чтобы ее можно было посмотреть в браузере не разворачивая проект из
  исходного кода) и вышлите нам ссылку.
* [x] Укажите в письме список выполненных пунктов и выбранные вами инструменты. Если Вы сделали не все пункты тестового
  задания – пожалуйста, укажите причину по которой Вы их не выполнили (не хватило времени, не хватает опыта/знания,
  что-то ещё).
* [ ] Обязательно приложите к письму максимально подробное и актуальное на дату отправки резюме.
* [x] Укажите ссылку на github/bitbucket репозиторий.
* [x] Укажите сколько часов было потрачено на выполнение задания.
* [ ] Высылайте выполненное тестового задание на электронный адрес hr@abz.agency.

### Task:

* [x] Ваша задача реализовать REST API сервер в соответствии
  с [API Documentation](https://apidocs.abz.dev/test_assignment_for_frontend_developer_api_documentation#) (OpenAPI).
* Генератор данных и seeders
*
    * [x] Реализовать генератор данных и seeders для первоначального заполнения БД данными юзером (45 юзеров).
*
    * [x] Данные должны быть максимально похожи на реальные (на En).

* Особенности POST запроса
*
    * [x] Картинку необходимо кропнуть (центр/центр) и сохранить как jpg 70х70px.
*
    * [x] Картинку необходимо оптимизировать с использованием API tinypng.com. Вы можете использовать любой другой API
      сервис для оптимизации изображений (мы используем kraken.io, но он только платный), просто укажите в описании к
      тестовому какой сервис был использован и почему именно он.
*
    * [x] Токен авторизации необходим только для того, чтобы продемонстрировать умение его генерации и использования.

* Реализовать фронтенд часть, только для демонстрации работы вашего сервера. Без дизайна и красивого оформления, можете
  использовать любые готовые UI компоненты, с которым вам удобнее работать. Мы будем смотреть только на вывод данных и
  на то что форма работает.
*
    * [x] Продемонстрировать вывод списка юзеров. С кнопкой “Показать еще” и выводом по 6 юзеров на странице.
*
    * [x] Продемонстрировать работающую форму. На фронтенд части никаких валидации не нужно, все валидации должны быть
      только на стороне сервера.

## Backlog:

4h Подготовка, инсталяция, первое знакомство.

2h get token

5h get users post users валидация аутентификация Сохранение фото

3h 30m post users:
Кастомная валидация

35m get users/{n} Валидация ошибок

3h 30m get users custom input collection and pagination

50m get Position

7h Testing

50m Локализация

1h 30m seeders with faker

30m set up API for image

13h frontend

1h small fixes

4h deploying

1h bug fixes

=== totaly 46h 15m

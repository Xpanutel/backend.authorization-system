# Документация проекта

Добро пожаловать в нашу документацию! Здесь вы найдёте всю необходимую информацию о проекте, включая требования к нему, описание функционала и другие важные детали.

## Требования к проекту

Проект представляет собой веб-приложение, которое включает в себя следующие компоненты:

- Форма регистрации пользователей.
- Система авторизации пользователей.
- Страница профиля для каждого зарегистрированного пользователя.

### Форма регистрации

Форма регистрации должна содержать следующие поля:

- Имя пользователя.
- Телефон пользователя.
- Адрес электронной почты пользователя.
- Два поля для ввода пароля (для подтверждения).

При заполнении формы важно учитывать следующие моменты:

- Все три поля (телефон, адрес электронной почты и логин) должны быть уникальными. Если такой пользователь уже существует в базе данных, система должна уведомить пользователя об этом.
- Пароли в двух полях должны совпадать. Если они не совпадают, система должна уведомить пользователя об этом.

### Система авторизации

Система авторизации должна позволять пользователям входить в систему, используя свой номер телефона или адрес электронной почты в качестве идентификатора. 
При входе также необходимо использовать пароль. 

### Страница профиля

Каждый пользователь, прошедший процесс регистрации, получает доступ к своей персональной странице профиля. Эта страница позволяет пользователю управлять своими личными данными, такими как имя, номер телефона и адрес электронной почты. Пользователь также может изменить свой пароль на этой странице.
Неавторизованные пользователи не имеют доступа к этой странице. Вместо этого они перенаправляются на главную страницу сайта.


```
| -- /components
|  -- header.php
|  -- footer.php
|-- /css
|   -- style.css
|   -- footer.css
|   -- header.css
|-- /img
|   -- logo.png
|-- /js
|   -- scripts.js
|-- /sql
|  -- users.sql
|  -- products.sql
|  -- -- store.sql
|-- index.php
|-- cart.php
|-- login.php
|-- register.php
|-- profile.php
|-- db_config.php
|-- .htaccess
```

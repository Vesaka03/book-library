# Уеб приложение "Библиотека" (Book Library)

Семпло и леко уеб приложение за управление на библиотека с книги. Изградено е с PHP (бекенд), HTML/CSS (фронтенд) и MySQL (база данни). Проектът е напълно контейнеризиран с Docker.
* **Frontend Image:** [vesko03/book-library-frontend](https://hub.docker.com/r/vesko03/book-library-frontend)
* **Backend Image:** [vesko03/book-library-backend](https://hub.docker.com/r/vesko03/book-library-backend)
## Структура на проекта
```
Book-Library/ 
├── frontend/              # Nginx сървър + HTML/CSS/JS
│   ├── index.html
│   ├── style.css
│   └── Dockerfile
├── backend/               # PHP API ендпоинти
│   ├── api.php
│   └── Dockerfile
├── db/                    # Инициализация на базата данни
│   └── init.sql
├── compose.yml            # Конфигурация на Docker Compose
└── README.md
```

## Компоненти

**Фронтенд (Порт 80)**
- Nginx сървър, който сервира HTML/CSS.
- Форма за добавяне на книги (Заглавие, Автор, Година).
- Динамично извеждане на книгите в адаптивна мрежа (responsive grid).
- JavaScript Fetch API за комуникация с бекенда.

**Бекенд (Порт 8000)**
- PHP 8.2 с Apache.
- Два API ендпоинта:
  - `GET /api.php?action=getBooks` - Извличане на всички книги.
  - `POST /api.php?action=addBook` - Добавяне на нова книга.
- PDO MySQL връзка с вградено управление на грешки.

**База данни (Порт 3306 - само вътрешен достъп)**
- MySQL с автоматична инициализация.
- Таблица `books`: id, title, author, year, created_at.
- Предварително попълнена с 5 примерни български книги.
- Персистентност (трайност) на данните чрез Docker volumes.

## Комуникация между услугите

- Фронтендът прави заявки към бекенда на адрес: `http://localhost:8000/api.php`.
- Бекендът се свързва към MySQL базата данни, използвайки името на услугата `db` (чрез Docker DNS резолюция).
- Всички услуги работят в споделена bridge мрежа с име `book-library-network`.
- Външни променливи (Environment variables): DB_HOST=db, DB_NAME=book_library, DB_USER=root, DB_PASSWORD=rootpassword.

## Бърз старт

```bash
# Компилиране и стартиране на всички услуги
docker compose up --build

# Достъп до приложението през браузъра
http://localhost

# Спиране на приложението
docker compose down

# Спиране на приложението и изтриване на записаните данни в базата
docker compose down -v

# Проверка на работещите контейнери
docker compose ps

# Преглед на логовете в реално време
docker compose logs -f

# Преглед на логовете само за бекенда
docker compose logs -f backend
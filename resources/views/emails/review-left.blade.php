<!DOCTYPE html>
<html>
<body>
<h1>Добавлен новый отзыв</h1>
Для модерации перейдите по ссылке:
<a href="{{ route('zeusAdmin.section.edit.form', [
            'section' => 'Reviews',
            'id' => $review['id']
            ])
        }}"
>Модерировать</a>
</body>
</html>
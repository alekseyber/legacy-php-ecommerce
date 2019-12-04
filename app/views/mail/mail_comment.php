<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
    </head>
    <body>

        <table style="border: 1px solid #ddd; border-collapse: collapse; width: 100%;">
            <thead>
                <tr style="background: #f9f9f9;">
                    <th colspan="2" style="padding: 8px; border: 1px solid #ddd; background: #f9f9f9;">Данные сообщения</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding: 8px; border: 1px solid #ddd; background: #f9f9f9; width: 30%;">Имя:</td>
                    <td style="padding: 8px; border: 1px solid #ddd; width: 70%;"><?= $author_name ?></td>
                </tr>
                <tr>
                    <td style="padding: 8px; border: 1px solid #ddd; background: #f9f9f9; width: 30%;">Авторизация</td>
                    <td style="padding: 8px; border: 1px solid #ddd; width: 70%;"><?= $author_provider ?></td>
                </tr>
                <tr>
                    <td style="padding: 8px; border: 1px solid #ddd; background: #f9f9f9; width: 30%;">Отзыв:</td>
                    <td style="padding: 8px; border: 1px solid #ddd; width: 70%;"><?= $commen_text ?></td>
                </tr>
                <tr>
                    <td style="padding: 8px; border: 1px solid #ddd; background: #f9f9f9; width: 30%;">IP:</td>
                    <td style="padding: 8px; border: 1px solid #ddd; width: 70%;"><?= long2ip($ip) ?></td>
                </tr>
            </tbody>        
        </table>
    </body>
</html>
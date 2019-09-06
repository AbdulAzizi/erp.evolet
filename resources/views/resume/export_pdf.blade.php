<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif; 
            font-size: 14px;
        }
        .container{
            padding: 3%;
        }
        img{
            height: 25px;
            width: 100px;
        }
        hr {
            border: 0.2px solid #d8d8d8;
        }
    </style>
<body>
    <div>
        <img src="../public/img/dark-logo.png" alt="evolet-logo">
    </div>
    <div class="container">
        <h4>Основное</h4>
        <p>ФИО: {{$resume->name}} {{$resume->surname}}</p>
        <p>Дата рождения: {{date('d.m.Y', strtotime($resume->birthday))}}</p>
        <p>Пол: {{$resume->male_female}}</p>
        <p>Военная обязанность: {{$resume->military_status}}</p>
        <p>Телефон: {{$resume->phone}}</p>
        <h4>Образование</h4>
        @foreach ($resume->educations as $key => $education)
            <p>Название: {{$education->name}}</p>
            <p>Степень: {{$education->degree}}</p>
            <p>Специальность: {{$education->specialty}}</p>
            <p>Период обучения: {{date('d.m.Y', strtotime($education->start_at))}} - {{date('d.m.Y', strtotime($education->end_at))}}</p>
            @if ($resume->educations->count() > $key + 1)
                <hr>
            @endif
        @endforeach
        <h4>Опыт работы</h4>
        @foreach ($resume->jobs as $key => $job)
            <p>Название компании: {{$job->company_name}}</p>
            <p>Позиция: {{$job->position}}</p>
            <p>Местоположение: {{$job->location}}</p>
            <p>Период работы: {{date('d.m.Y', strtotime($job->start_at))}} - {{date('d.m.Y', strtotime($job->end_at))}}</p>
            @if ($resume->jobs->count() > $key + 1)
                <hr>
            @endif
        @endforeach
        <h4>Семья</h4>
        @foreach ($resume->families as $key => $family)
            <p>Имя: {{$family->name}}</p>
            <p>Степень родства: {{$family->relation}}</p>
            <p>Дата рождения: {{date('d.m.Y', strtotime($family->birthday))}}</p>
            @if ($resume->families->count() > $key + 1)
                <hr>
            @endif
        @endforeach
        <h4>Знание языков</h4>
        @foreach ($resume->languages as $key => $language)
            <p>Язык: {{$language->name}}</p>
            <p>Уровень: {{$language->level}}</p>
            @if ($resume->languages->count() > $key + 1)
                <hr>
            @endif
        @endforeach
        <h4>Достижения</h4>
        @foreach ($resume->achievments as $key => $achievment)
            <p>Тип: {{$achievment->type}}</p>
            <p>Описание: {{$achievment->description}}</p>
            @if ($resume->achievments->count() > $key + 1)
                <hr>
            @endif
        @endforeach
    </div>
    </div>
</body>
</html>
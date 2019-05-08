<?php

use Illuminate\Database\Seeder;

class DivisionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = \App\Division::create([
            'name' => 'Evolet',
            'abbreviation' => 'Evolet',

            'children' => [
                [
                    'name' => 'Департамент Информационных Технологий',
                    'abbreviation' => 'ДИТ',

                    'children' => [
                        [
                            'name' => 'Подразделение по Информационным Технологиям при ГО',
                            'abbreviation' => 'ПИТ',

                            'children' => [
                                [
                                    'name' => 'Отдел Информационных Технологии в Европе',
                                    'abbreviation' => 'ОИТЕ',
                                ],
                                [
                                    'name' => 'Отдел Информационных Технологии в Индии',
                                    'abbreviation' => 'ОИТИ',
                                ],
                                [
                                    'name' => 'Отдел Информационных Технологии в Странах Регистрации',
                                    'abbreviation' => 'ОИТСР',
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'name' => 'Департамент Маркетинга',
                    'abbreviation' => 'ДМ',

                    'children' => [
                        [
                            'name' => 'Научно Аналитическое Подразделение',
                            'abbreviation' => 'НАП',

                            'children' => [
                                [
                                    'name' => 'Научный Отдел',
                                    'abbreviation' => 'НО',
                                ],
                                [
                                    'name' => 'Отдел Мониторинг Анализ Рынка',
                                    'abbreviation' => 'ОМАР',
                                ],
                                [
                                    'name' => 'Отдел Мониторинга Обновление Портфеля',
                                    'abbreviation' => 'ОМОП',
                                ],
                                [
                                    'name' => 'Отдел Торговых Знаков',
                                    'abbreviation' => 'ОТЗ',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Подразделение по Продаже Продуктов',
                            'abbreviation' => 'ППП',

                            'children' => [
                                [
                                    'name' => 'Отдел Аудита Маркетинга',
                                    'abbreviation' => 'ОАМ',
                                ],
                                [
                                    'name' => 'Отдел Проектного Управления',
                                    'abbreviation' => 'ОПУ',
                                ],
                                [
                                    'name' => 'Отдел Цифрового Маркетинга',
                                    'abbreviation' => 'ОЦМ',
                                ],
                                [
                                    'name' => 'Промо Компания',
                                    'abbreviation' => 'ПК',

                                    'children' => [
                                        [
                                            'name' => 'Vegapharm',
                                            'abbreviation' => 'V',
                                        ],
                                        [
                                            'name' => 'Belinda',
                                            'abbreviation' => 'B',
                                        ],
                                        [
                                            'name' => 'Spey',
                                            'abbreviation' => 'S',
                                        ],
                                        [
                                            'name' => 'Neo Universe',
                                            'abbreviation' => 'N',
                                        ],
                                        [
                                            'name' => 'Lady Healthcare',
                                            'abbreviation' => 'L',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'name' => 'Департамент Производства',
                    'abbreviation' => 'ДП',

                    'children' => [
                        [
                            'name' => 'Подразделение по Производству в Европе',
                            'abbreviation' => 'ППЕ',
                        ],
                        [
                            'name' => 'Подразделение по Производству в Индии',
                            'abbreviation' => 'ППИ',

                            'children' => [
                                [
                                    'name' => 'Отдел Информационных Технологии',
                                    'abbreviation' => 'ОИТ',
                                ],
                                [
                                    'name' => 'Отдел Управление Проектами',
                                    'abbreviation' => 'ОУП',
                                ],
                                [
                                    'name' => 'Отдел Финансов',
                                    'abbreviation' => 'ОФ',
                                ],
                                [
                                    'name' => 'Отдел Экспорта',
                                    'abbreviation' => 'ОЭ',
                                ],
                                [
                                    'name' => 'Отдел Юристов',
                                    'abbreviation' => 'ОЮ',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Подразделение по Производству при ГО',
                            'abbreviation' => 'ППГ',

                            'children' => [
                                [
                                    'name' => 'Отдел Дизайна',
                                    'abbreviation' => 'ОД',
                                ],
                                [
                                    'name' => 'Отдел Закупов',
                                    'abbreviation' => 'ОЗ',
                                ],
                                [
                                    'name' => 'Отдел Логистики',
                                    'abbreviation' => 'ОЛ',
                                ],
                                [
                                    'name' => 'Отдел по Мониторингу и Анализу Рынка',
                                    'abbreviation' => 'ОМАР',
                                ],
                                [
                                    'name' => 'Отдел Регистрации',
                                    'abbreviation' => 'ОР',
                                ],
                                [
                                    'name' => 'Отдел Технического и Химического контроля',
                                    'abbreviation' => 'ОТХ',
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'name' => 'Департамент Финансов',
                    'abbreviation' => 'ДФ',

                    'children' => [
                        [
                            'name' => 'Подразделение Финансов при ГО',
                            'abbreviation' => 'ПФГ',

                            'children' => [
                                [
                                    'name' => 'Отдел Финансов в Европе',
                                    'abbreviation' => 'ОФЕ',
                                ],
                                [
                                    'name' => 'Отдел Финансов в Индии',
                                    'abbreviation' => 'ОФИ',
                                ],
                                [
                                    'name' => 'Отдел Финансов в Странах Регистрации',
                                    'abbreviation' => 'ОСР',
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'name' => 'Департамент Человеческих Ресурсов',
                    'abbreviation' => 'ДЧР',

                    'children' => [
                        [
                            'name' => 'Корпоративный Учебный Центр',
                            'abbreviation' => 'КУЦ',
                        ],
                        [
                            'name' => 'Подразделение Человеческих Ресурсов',
                            'abbreviation' => 'ПЧР',

                            'children' => [
                                [
                                    'name' => 'Отдел Управление Персоналом в Европе',
                                    'abbreviation' => 'ОУПЕ',
                                ],
                                [
                                    'name' => 'Отдел Управление Персоналом в Индии',
                                    'abbreviation' => 'ОУПИ',
                                ],
                                [
                                    'name' => 'Отдел Управление Персоналом в ПК',
                                    'abbreviation' => 'ОУПП',
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'name' => 'Департамент Юридических Аспектов',
                    'abbreviation' => 'ДЮА',

                    'children' => [
                        [
                            'name' => 'Подразделение по Юридическим аспектам при ГО',
                            'abbreviation' => 'ПЮГ',

                            'children' => [
                                [
                                    'name' => 'Отдел по Юридическим аспектам в Европе',
                                    'abbreviation' => 'ОЮЕ',
                                ],
                                [
                                    'name' => 'Отдел по Юридическим аспектам в Индии',
                                    'abbreviation' => 'ОЮИ',
                                ],
                                [
                                    'name' => 'Отдел по Юридическим аспектам в Странах Регистрации',
                                    'abbreviation' => 'ОЮСР',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }
}

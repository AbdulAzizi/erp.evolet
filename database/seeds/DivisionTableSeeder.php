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
                    'name' => 'Департамент Маркетинга',
                    'abbreviation' => 'ДМ',

                    'children' => [
                        [
                            'name' => 'Научно Аналитическое Подразделение',
                            'abbreviation' => 'НАП',

                            'children' => [
                                [
                                    'name' => 'Отдел Мониторинг Анализ Рынка',
                                    'abbreviation' => 'ОМАР',
                                ],
                                [
                                    'name' => 'Научный Отдел',
                                    'abbreviation' => 'НО',
                                ],
                                [
                                    'name' => 'Отдел Торговых Знаков',
                                    'abbreviation' => 'ОТЗ',
                                ],
                                [
                                    'name' => 'Отдел Мониторинга Обновление Портфеля',
                                    'abbreviation' => 'ОМОП',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Подразделение по Продаже Продуктов',
                            'abbreviation' => 'ППП',

                            'children' => [
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
                                            'name' => 'Belinda Ophtolmology',
                                            'abbreviation' => 'BO',
                                        ],
                                        [
                                            'name' => 'Lady Healthcare',
                                            'abbreviation' => 'L',
                                        ],
                                    ],
                                ],
                                [
                                    'name' => 'Научный Отдел',
                                    'abbreviation' => 'НО',
                                ],
                                [
                                    'name' => 'Отдел Дизайна',
                                    'abbreviation' => 'ОД',
                                ],
                                [
                                    'name' => 'Отдел Цифрового Маркетинга',
                                    'abbreviation' => 'ОЦМ',
                                ],
                                [
                                    'name' => 'Отдел Проектного Управления',
                                    'abbreviation' => 'ОПУ',
                                ],
                                [
                                    'name' => 'Отдел Аудита Маркетинга',
                                    'abbreviation' => 'ОАМ',
                                ],
                            ],
                        ],
                    ],
                ],[
                    'name' => 'Департамент Производства',
                    'abbreviation' => 'ДП',

                    'children' => [
                        [
                            'name' => 'Подразделение по Производству при ГО',
                            'abbreviation' => 'ППГ',

                            'children' => [
                                [
                                    'name' => 'Отдел по Мониторингу и Анализу Производителей',
                                    'abbreviation' => 'ОМАП',
                                ],
                                [
                                    'name' => 'Отдел Дизайна',
                                    'abbreviation' => 'ОД',
                                ],
                                [
                                    'name' => 'Отдел Технического и Химического Контроля',
                                    'abbreviation' => 'ОТХЧ',
                                ],
                                [
                                    'name' => 'Отдел Регистрации',
                                    'abbreviation' => 'ОР',
                                ],
                                [
                                    'name' => 'Отдел Закупов',
                                    'abbreviation' => 'ОЗк',
                                ],
                                [
                                    'name' => 'Отдел Логистики',
                                    'abbreviation' => 'Олог',
                                ],
                                
                            ],
                        ],
                        [
                            'name' => 'Подразделение по Производству в Европе',
                            'abbreviation' => 'ППЕ',

                            'children' => [
                                [
                                    'name' => 'Отдел Финансов',
                                    'abbreviation' => 'ОФс',
                                ],
                                [
                                    'name' => 'Отдел Управление Персоналом',
                                    'abbreviation' => 'ОУП',
                                ],
                                [
                                    'name' => 'Отдел Юристов',
                                    'abbreviation' => 'ОЮ',
                                ],
                                [
                                    'name' => 'Отдел Информационных Технологии',
                                    'abbreviation' => 'ОИТ',
                                ],
                                [
                                    'name' => 'Отдел Регистрации',
                                    'abbreviation' => 'ОР',
                                ],
                                [
                                    'name' => 'Отдел Закупов',
                                    'abbreviation' => 'ОЗк',
                                ],
                                [
                                    'name' => 'Отдел Логистики',
                                    'abbreviation' => 'Олог',
                                ],
                                [
                                    'name' => 'Отдел Экспорта',
                                    'abbreviation' => 'ОЭкс',
                                ],
                                [
                                    'name' => 'Отдел Дизайна',
                                    'abbreviation' => 'ОД',
                                ],
                            ],
                        ],
                        [
                            'name' => 'Подразделение по Производству в Индии',
                            'abbreviation' => 'ППИ',

                            'children' => [[
                                    'name' => 'Отдел Финансов',
                                    'abbreviation' => 'ОФс',
                                ],
                                [
                                    'name' => 'Отдел Управление Персоналом',
                                    'abbreviation' => 'ОУП',
                                ],
                                [
                                    'name' => 'Отдел Юристов',
                                    'abbreviation' => 'ОЮ',
                                ],
                                [
                                    'name' => 'Отдел Информационных Технологии',
                                    'abbreviation' => 'ОИТ',
                                ],
                                [
                                    'name' => 'Отдел Регистрации',
                                    'abbreviation' => 'ОР',
                                ],
                                [
                                    'name' => 'Отдел Закупов',
                                    'abbreviation' => 'ОЗк',
                                ],
                                [
                                    'name' => 'Отдел Логистики',
                                    'abbreviation' => 'Олог',
                                ],
                                [
                                    'name' => 'Отдел Экспорта',
                                    'abbreviation' => 'ОЭкс',
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'name' => 'Департамент Человеческих Ресурсов',
                    'abbreviation' => 'ДЧ',

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
                                    'name' => 'Отдел Управление Персоналом в Индии',
                                    'abbreviation' => 'ОУПИ',
                                ],
                                [
                                    'name' => 'Отдел Управление Персоналом в Европе',
                                    'abbreviation' => 'ОУПЕ',
                                ],
                                [
                                    'name' => 'Отдел Управление Персоналом в Странах/ПК',
                                    'abbreviation' => 'ОУПС',
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
                                    'name' => 'Отдел Финансов в Странах/ПК',
                                    'abbreviation' => 'ОФС',
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'name' => 'Департамент Юридических Аспектов',
                    'abbreviation' => 'ДЮ',

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
                                    'name' => 'Отдел по Юридическим аспектам в Странах/ПК',
                                    'abbreviation' => 'ОЮС',
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'name' => 'Департамент Информационных Технологий',
                    'abbreviation' => 'ДИ',

                    'children' => [
                        [
                            'name' => 'Подразделение по Информационным Технологиям при ГО',
                            'abbreviation' => 'ПИГ',

                            'children' => [
                                [
                                    'name' => 'Отдел Информационных Технологии в Европе',
                                    'abbreviation' => 'ОИЕ',
                                ],
                                [
                                    'name' => 'Отдел Информационных Технологии в Индии',
                                    'abbreviation' => 'ОИИ',
                                ],
                                [
                                    'name' => 'Отдел Информационных Технологии в Странах/ПК',
                                    'abbreviation' => 'ОИС',
                                ],
                                [
                                    'name' => 'Отдел Разработки Програмных Обеспечений',
                                    'abbreviation' => 'ОРПО',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }
}

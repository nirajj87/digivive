<?php

namespace App\Enums;

class Department
{
    const SALES = ['title' => 'Sales', 'slug' => 'sales'];
    const FINANCE = ['title' => 'Finance', 'slug' => 'finance'];
    const MARKETING = ['title' => 'Marketing', 'slug' => 'marketing'];
    const OPERATIONS_MANAGEMENT = ['title' => 'Operations Management', 'slug' => 'operations-management'];
    const HUMAN_RESOURCES = ['title' => 'Human Resources', 'slug' => 'human-resources'];
    const INFORMATION_TECHNOLOGY = ['title' => 'Information Technology', 'slug' => 'information-technology'];

    public static function toArray()
    {
        return [
            self::SALES,
            self::FINANCE,
            self::MARKETING,
            self::OPERATIONS_MANAGEMENT,
            self::HUMAN_RESOURCES,
            self::INFORMATION_TECHNOLOGY,
        ];
    }
}
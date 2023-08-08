<?php

declare(strict_types=1);

namespace Kishlin\Backend\Persistence\Migration;

interface Migration
{
    public function up(): string;

    public function down(): string;
}

<?php

namespace App\Filament\Widgets;

use App\Models\Divisi;
use Filament\Forms\Components\DatePicker;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class DivisionStatsTable extends BaseWidget
{
    protected static ?string $heading = 'Monitoring Kunjungan Divisi';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                $filterData = $this->tableFilters['periode'] ?? null;
                $from = $filterData['created_from'] ?? null;
                $until = $filterData['created_until'] ?? null;

                return Divisi::query()->withCount([
                    // Hitung IN (id_visit_status = 2)
                    'tamus as in_count' => function (Builder $query) use ($from, $until) {
                        $query->where('id_visit_status', 2);
                        if ($from) $query->whereDate('created_at', '>=', $from);
                        if ($until) $query->whereDate('created_at', '<=', $until);
                    },
                    // Hitung OUT (id_visit_status = 5)
                    'tamus as out_count' => function (Builder $query) use ($from, $until) {
                        $query->where('id_visit_status', 5);
                        if ($from) $query->whereDate('created_at', '>=', $from);
                        if ($until) $query->whereDate('created_at', '<=', $until);
                    },
                    // Hitung TOTAL
                    'tamus as total_count' => function (Builder $query) use ($from, $until) {
                        if ($from) $query->whereDate('created_at', '>=', $from);
                        if ($until) $query->whereDate('created_at', '<=', $until);
                    }
                ]);
            })
            // --- PERBAIKAN: defaultSort ditaruh DISINI (di chain $table), bukan di dalam query ---
            ->defaultSort('in_count', 'desc')

            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('No')
                    ->rowIndex(),

                Tables\Columns\TextColumn::make('nama_divisi')
                    ->label('Divisi')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('in_count')
                    ->label('IN (Masuk)')
                    ->sortable()
                    ->badge()
                    ->color('success')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('out_count')
                    ->label('OUT (Selesai)')
                    ->sortable()
                    ->badge()
                    ->color('danger')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('total_count')
                    ->label('Total Tamu')
                    ->sortable()
                    ->alignCenter(),
            ])
            ->filters([
                Filter::make('periode')
                    ->form([
                        DatePicker::make('created_from')->label('Tanggal Awal'),
                        DatePicker::make('created_until')->label('Tanggal Akhir')->default(now()),
                    ])
                    ->query(fn(Builder $query) => $query) // Query kosong agar tidak memfilter baris divisi
            ]);
    }
}

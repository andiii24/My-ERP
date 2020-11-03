<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $pie = $this->pie();

        $pie2 = $this->pie2();

        $chartjs = $this->chartjs();

        return view('home', compact('chartjs', 'pie', 'pie2'));
    }

    public function chartjs()
    {
        $chartjs = app()->chartjs
            ->name('barChartTest')
            ->type('bar')
            ->size(['width' => 444, 'height' => 200])
            ->labels(['Raw Materials', 'MRO Items', 'Finished Goods'])
            ->datasets([
                [
                    "label" => "Percentage",
                    'backgroundColor' => ['rgba(61, 134, 96, 0.5)', 'rgba(134, 61, 99, 0.5)', 'rgba(134, 132, 61, 0.5)', 'rgba(61, 99, 134, 0.5)'],
                    'borderColor' => ['rgba(61, 134, 96, 1)', 'rgba(134, 61, 99, 1)', 'rgba(134, 132, 61, 1)', 'rgba(61, 99, 134, 1)'],
                    'borderWidth' => 1,
                    'data' => [85, 50, 60, 44],
                ],
            ])
            ->options([
                'legend' => [
                    'display' => false,
                ],
                'scales' => [
                    'xAxes' => [
                        [
                            'stacked' => false,
                            'gridLines' => [
                                'display' => false,
                            ],
                            'barPercentage' => 0.7,
                        ],
                    ],
                    'yAxes' => [
                        [
                            'stacked' => true,
                            'gridLines' => [
                                'display' => true,
                            ],
                        ],
                    ],
                ],
            ]);

        return $chartjs;
    }

    public function pie()
    {
        $pie = app()->chartjs
            ->name('pieChartTest')
            ->type('doughnut')
            ->size(['width' => 191, 'height' => 200])
            ->labels(['Plastics', 'Coal'])
            ->datasets([
                [
                    'backgroundColor' => ['rgba(61, 134, 96, 0.5)', 'rgba(134, 61, 99, 0.5)'],
                    'borderColor' => ['rgba(61, 134, 96, 1)', 'rgba(134, 61, 99, 1)'],
                    'borderWidth' => 1,
                    'data' => [30, 70],
                ],
            ])
            ->options([
                'cutoutPercentage' => 50,
            ]);

        return $pie;
    }

    public function pie2()
    {
        $pie2 = app()->chartjs
            ->name('pie2ChartTest')
            ->type('doughnut')
            ->size(['width' => 191, 'height' => 200])
            ->labels(['Sesame', 'Coffee'])
            ->datasets([
                [
                    'backgroundColor' => ['rgba(61, 134, 96, 0.5)', 'rgba(134, 61, 99, 0.5)'],
                    'borderColor' => ['rgba(61, 134, 96, 1)', 'rgba(134, 61, 99, 1)'],
                    'borderWidth' => 1,
                    'data' => [60, 40],
                ],
            ])
            ->options([
                'cutoutPercentage' => 50,
            ]);

        return $pie2;
    }
}

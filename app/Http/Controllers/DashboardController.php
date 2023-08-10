<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Helpers\Sdwan;
use App\Library\Ajax;
use App\Models\Bond;
use App\Models\Safelink;
use App\Models\Space;
use Illuminate\Http\Request;

use \Illuminate\Support\Facades\View as View;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('dashboard.index');
    }

    public function dashboardInfo(Request $request, Ajax $ajax){
        $space = Space::find($request->input('space'));

        if(!$space){
            return $ajax->fail()
                ->jscallback()
                ->message('Space not found!')
                ->response();
        }

        $request_body = [
            'SDWAN' => [
                'email' => config('constant.SDWAN.email'),
                'password' => config('constant.SDWAN.password')
            ]
        ];

       /* $space_object_counts = Sdwan::space_object_counts($space, $request_body);
        $space_object_counts = (array) json_decode($space_object_counts, true);*/
        $dashboard_info = [];
        $bonds_info = [];
        $total_bonds = $total_circuit = $online_bond = $offline_bond = $up_circuit = $down_circuit = 0;
        $bondsHtml = '';

        $bonds = Sdwan::get_bonds($request_body, $space->key);
        $bonds = (array) json_decode($bonds, true);

        $filterBy = $space->key;
        $bonds = array_filter($bonds, function ($var) use ($filterBy) {
            return ($var['space']['key'] == $filterBy);
        });

        $total_bonds = count($bonds);
        $up_speed = $down_speed = 0;

        foreach ($bonds as $bond){
            $legs = Sdwan::get_legs($request_body);
            $legs = (array) json_decode($legs, true);
            $filterBy = $bond['id'];
            $legs = array_filter($legs, function ($var) use ($filterBy) {
                return ($var['bond_id'] == $filterBy);
            });

            $bondsHtml .= '<tr>
            <td>'.$bond['id'].'</td>
            <td>'.$bond['name'].'</td>';
            $up_speed += $bond['upload_rate'];
            $down_speed += $bond['download_rate'];
            $legs_info = [];

            $legs_icons = [];
            $up_count = 0;
            if(count($legs) > 0){
                foreach ($legs as $leg){
                    if($leg['overall_state'] == 'up'){
                        ++$up_count;
                        ++$up_circuit;
                        $legs_icons[] = '<a href="javascript:void(0)" class="m-1" style="color: #36800a;"><i class="fa fa-circle"></i></a>';
                    }else{
                        $legs_icons[] = '<a href="javascript:void(0)" class="m-1"><i class="fa fa-warning" style="color:red"></i></a>';
                        ++$down_circuit;
                    }

                    /*$legs_info[] = [
                        'id' => $leg['id'],
                        'bond_id' => $leg['bond_id'],
                        'down_speed' => $leg['down_speed'],
                        'up_speed' => $leg['up_speed'],
                        'overall_state' => $leg['overall_state'],
                    ];*/
                    ++$total_circuit;
                }
            }else{
                $legs_icons[] = '<a href="javascript:void(0)" class="m-1"><i class="fa fa-warning" style="color:red"></i></a>';
                $legs_icons[] = '<a href="javascript:void(0)" class="m-1"><i class="fa fa-warning" style="color:red"></i></a>';
                $legs_icons[] = '<a href="javascript:void(0)" class="m-1"><i class="fa fa-warning" style="color:red"></i></a>';

            }

            if($up_count > 0){
                ++$online_bond;
            }else{
                ++$offline_bond;
            }
//Helper::formatBytes($up_speed, 2)
            $bondsHtml .= '<td>'.implode('', $legs_icons).'</td>';
            $bondsHtml .= '<td>'.number_format(($up_speed/1024), 2).' Kbps</td>';
            $bondsHtml .= '<td>'.number_format(($down_speed/1024), 2).' Kbps</td>
            </tr>';
            $bonds_address = Safelink::where('safelink_id', $bond['circuit_id'])->first();
            $bonds_info[] = [
                'id' => $bond['id'],
                'name' => $bond['name'],
                'address' => isset($bonds_address['address']) ? $bonds_address['address'] : '',
                'lat' => isset($bonds_address['lat']) ? $bonds_address['lat'] : '',
                'long' => isset($bonds_address['long']) ? $bonds_address['long'] : '',
                'legs' => $legs_info,
                'online_bond' => $online_bond,
                'offline_bond' => $offline_bond,
            ];

        }

        $data = [
            'bond_html' => $bondsHtml,
            'total_bonds' => $total_bonds,
            'total_circuit' => $total_circuit,
            'online_bond' => $online_bond,
            'offline_bond' => $offline_bond,
            'up_circuit' => $up_circuit,
            'down_circuit' => $down_circuit,
        ];

        $view = View::make('dashboard.space-details', $data)->render();

        return $ajax->success()
            ->appendParam('html', $view)
            ->appendParam('bonds_info', $bonds_info)
            ->jscallback('show_dashboard_ajax')
            ->response();
    }
}

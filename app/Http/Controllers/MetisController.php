<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MetisController extends Controller
{
    //
    public function getUserIp(){  

        // Gettingip address of  user     
        $ip= request()->ip();
        return $ip;
    }
    public function macAddress() 
    {
        // Gettingip macaddress
        return substr(exec('getmac'), 0, 17); 
    }
    public function diskUsage()
    {
        $disktotal = disk_total_space('/'); //DISK usage
        $disktotalsize = $disktotal / 1073741824;

        $diskfree  = disk_free_space('/');
        $used = $disktotal - $diskfree;

        $diskusedsize = $used / 1073741824;
        $diskuse1   = round(100 - (($diskusedsize / $disktotalsize) * 100));
        $diskuse = round(100 - ($diskuse1)) . '%';
    
        $data = array(
            'diskuse' => $diskuse,
            'disktotalsize' => $disktotalsize,
            'diskusedsize' => $diskusedsize,
        );
         return $data;
    }
    public function totalRamCpuUsage() // CPU and Ram usage
    {
        //RAM usage
        $free = shell_exec('free'); 
        $free = (string) trim($free);
        $free_arr = explode("\n", $free);
        $mem = explode(" ", $free_arr[1]);
        $mem = array_filter($mem);
        $mem = array_merge($mem);
        $usedmem = $mem[2];
        $usedmemInGB = number_format($usedmem / 1048576, 2) . ' GB';
        $memory1 = $mem[2] / $mem[1] * 100;
        $memory = round($memory1) . '%';
        $fh = fopen('/proc/meminfo', 'r');
        $mem = 0;
        while ($line = fgets($fh)) {
            $pieces = array();
            if (preg_match('/^MemTotal:\s+(\d+)\skB$/', $line, $pieces)) {
                $mem = $pieces[1];
                break;
            }
        }
        fclose($fh);
        $totalram = number_format($mem / 1048576, 2) . ' GB';
        
        //cpu usage
        $cpu_load = sys_getloadavg(); 
        $load = $cpu_load[0] . '% / 100%';
        
        $data  = array(
            'memory' => $memory,
            'totalram' => $totalram,
            'usedmemInGB' => $usedmemInGB,
            'load' => $load
        );
        return  $data;
    }

    public function systemInfo()
    {
        $data = array(
            'cpuRamUsage'=> $this->totalRamCpuUsage(),
            'diskUsage'=> $this->diskUsage(),
            'ipAddress' => $this->getUserIp(),
            'macAddress' => $this->macAddress(),
        );
        return response()->json($data);
    }
    // public function myinfo($data) code for cron job
    // {
        
    //     $this->systemInfo($data);
    // }
    

}

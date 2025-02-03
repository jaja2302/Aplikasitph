<div style="font-family: Arial, sans-serif;">
    <p>Dengan hormat,</p>

    <p>Berikut disampaikan laporan progress terkait pengambilan titik koordinat TPH menggunakan Aplikasi Marker TPH hingga {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y, [pukul] HH:mm') }} WIB.</p>
    <table cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-bottom: 20px;">
        <tr>
            <td width="33%" valign="top">
                <table style="border-collapse: collapse; width: 95%;">
                    <tr>
                        <th colspan="2" style="border: 1px solid black; padding: 5px; background-color: #f8f9fa;">Regional I</th>
                    </tr>
                    <tr>
                        <th style="border: 1px solid black; padding: 5px; width: 60%;">Estate</th>
                        <th style="border: 1px solid black; padding: 5px; width: 40%;">Progress</th>
                    </tr>
                    @foreach($reportData[1]['estates'] ?? [] as $estate => $stats)
                    <tr>
                        @if($stats['progress_percentage'] == 0)
                        <td style="border: 1px solid black; padding: 5px;background-color: #FFFF00;color: #000000;">{{ $estate }}</td>
                        <td style="border: 1px solid black; padding: 5px; text-align: right;background-color: #FFFF00;color: #000000;">{{ $stats['progress_percentage'] }}%</td>
                        @else
                        <td style="border: 1px solid black; padding: 5px;">{{ $estate }}</td>
                        <td style="border: 1px solid black; padding: 5px; text-align: right;">{{ $stats['progress_percentage'] }}%</td>
                        @endif
                    </tr>
                    @endforeach
                    <tr>
                        <td style="border: 1px solid black; padding: 5px; text-align: left;background-color: #C5E1A5;">TOTAL</td>
                        <td style="border: 1px solid black; padding: 5px; text-align: right;background-color: #C5E1A5;">{{ $reportData[1]['summary']['progress_percentage'] }}%</td>
                    </tr>
                </table>
            </td>
            <td width="33%" valign="top">
                <table style="border-collapse: collapse; width: 95%;">
                    <tr>
                        <th colspan="2" style="border: 1px solid black; padding: 5px; background-color: #f8f9fa;">Regional II</th>
                    </tr>
                    <tr>
                        <th style="border: 1px solid black; padding: 5px; width: 60%;">Estate</th>
                        <th style="border: 1px solid black; padding: 5px; width: 40%;">Progress</th>
                    </tr>
                    @foreach($reportData[2]['estates'] ?? [] as $estate => $stats)
                    <tr>
                        @if($stats['progress_percentage'] == 0)
                        <td style="border: 1px solid black; padding: 5px;background-color: #FFFF00;color: #000000;">{{ $estate }}</td>
                        <td style="border: 1px solid black; padding: 5px; text-align: right;background-color: #FFFF00;color: #000000;">{{ $stats['progress_percentage'] }}%</td>
                        @else
                        <td style="border: 1px solid black; padding: 5px;">{{ $estate }}</td>
                        <td style="border: 1px solid black; padding: 5px; text-align: right;">{{ $stats['progress_percentage'] }}%</td>
                        @endif
                    </tr>
                    @endforeach
                    <tr>
                        <td style="border: 1px solid black; padding: 5px; text-align: left;background-color: #C5E1A5;">TOTAL</td>
                        <td style="border: 1px solid black; padding: 5px; text-align: right;background-color: #C5E1A5;">{{ $reportData[2]['summary']['progress_percentage'] }}%</td>
                    </tr>
                </table>
            </td>
            <td width="33%" valign="top">
                <table style="border-collapse: collapse; width: 95%;">
                    <tr>
                        <th colspan="2" style="border: 1px solid black; padding: 5px; background-color: #f8f9fa;">Regional III</th>
                    </tr>
                    <tr>
                        <th style="border: 1px solid black; padding: 5px; width: 60%;">Estate</th>
                        <th style="border: 1px solid black; padding: 5px; width: 40%;">Progress</th>
                    </tr>
                    @foreach($reportData[3]['estates'] ?? [] as $estate => $stats)
                    <tr>
                        @if($stats['progress_percentage'] == 0)
                        <td style="border: 1px solid black; padding: 5px;background-color: #FFFF00;color: #000000;">{{ $estate }}</td>
                        <td style="border: 1px solid black; padding: 5px; text-align: right;background-color: #FFFF00;color: #000000;">{{ $stats['progress_percentage'] }}%</td>
                        @else
                        <td style="border: 1px solid black; padding: 5px;">{{ $estate }}</td>
                        <td style="border: 1px solid black; padding: 5px; text-align: right;">{{ $stats['progress_percentage'] }}%</td>
                        @endif
                    </tr>
                    @endforeach
                    <tr>
                        <td style="border: 1px solid black; padding: 5px; text-align: left;background-color: #C5E1A5;">TOTAL</td>
                        <td style="border: 1px solid black; padding: 5px; text-align: right;background-color: #C5E1A5;">{{ $reportData[3]['summary']['progress_percentage'] }}%</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <p>Demikian laporan ini disampaikan. Atas perhatian dan kerja samanya, diucapkan terima kasih.</p>
    <p>Salam,</p>
</div>
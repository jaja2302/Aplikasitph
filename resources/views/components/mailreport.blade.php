<div style="font-family: Arial, sans-serif;">
    <p>Dengan hormat,</p>

    <p>Berikut kami lampirkan progress pengambilan TPH menggunakan Aplikasi Marker TPH sampai dengan {{ \Carbon\Carbon::parse($latestUpdate)->locale('id')->isoFormat('D MMMM Y, [jam] HH:mm') }} WIB.</p>

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
                        <td style="border: 1px solid black; padding: 5px;">{{ $estate }}</td>
                        <td style="border: 1px solid black; padding: 5px; text-align: right;">{{ $stats['progress_percentage'] }}%</td>
                    </tr>
                    @endforeach
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
                        <td style="border: 1px solid black; padding: 5px;">{{ $estate }}</td>
                        <td style="border: 1px solid black; padding: 5px; text-align: right;">{{ $stats['progress_percentage'] }}%</td>
                    </tr>
                    @endforeach
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
                        <td style="border: 1px solid black; padding: 5px;">{{ $estate }}</td>
                        <td style="border: 1px solid black; padding: 5px; text-align: right;">{{ $stats['progress_percentage'] }}%</td>
                    </tr>
                    @endforeach
                </table>
            </td>
        </tr>
    </table>

    <p>Demikian kami sampaikan, terima kasih.</p>
    <p>Salam,</p>
</div>
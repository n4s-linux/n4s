<?php
function getansvar($kundenr) {                                                                                $q = ("select ansvarlig from responsible where kundenr=$kundenr and datediff(now(),updated)<1");
        $res = mysqli_query($GLOBALS["___mysqli_ston"], $q) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
        if (!mysqli_num_rows($res)) {
                return trim(strtolower(update_ansvar($kundenr)));
        }
        else {
                $arr = mysqli_fetch_assoc($res );
                return trim(strtolower($arr['ansvarlig']));
        }

}
function update_ansvar($kundenr) {
        global $client;
        $curansv = $client->Debtor_GetOurReference(array('debtorHandle'=>array('Number'=>$kundenr)));
        if (isset($curansv->Debtor_GetOurReferenceResult)) {
                $emps = $client->Employee_GetAll()->Employee_GetAllResult;
                $empdatas = $client->Employee_GetDataArray(array('entityHandles'=>$emps));
                $emp = array();
                $i = 0;
                foreach ($empdatas->Employee_GetDataArrayResult->EmployeeData as $data) {
                        $emp[$data->Number] = $data->Name;
                        $matches[$i++] = $data->Name;
                        $numbers[$i-1] = $data->Number;
                }
                $curansv = strtolower($emp[$curansv->Debtor_GetOurReferenceResult->Number]);
                mysqli_query($GLOBALS["___mysqli_ston"], "delete from responsible where kundenr=$kundenr;") or die(mysqli_error($GLOBALS["___mysqli_ston"]));
                mysqli_query($GLOBALS["___mysqli_ston"], "insert into responsible (kundenr,ansvarlig,updated) values ($kundenr,'$curansv',now());") or die(mysqli_error($GLOBALS["___mysqli_ston"]));
                return strtolower($curansv);
        }
}
?>

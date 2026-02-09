php
$logs = \App\Models\ActivityLog::all();
foreach ($logs as $log) {
    $log->created_at = $log->created_at->addHours(7);
    $log->save();
}
echo "Updated " . count($logs) . " records";

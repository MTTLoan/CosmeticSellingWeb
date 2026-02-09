<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChangeLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChangeLogController extends Controller
{
    /**
     * Hiển thị danh sách thay đổi.
     */
    public function index()
    {
        $changeLogs = ChangeLog::with('employee')->orderBy('changed_at', 'desc')->paginate(20);
        return view('admin.change-logs.index', compact('changeLogs'));
    }

    /**
     * Khôi phục thay đổi (một dòng).
     */
    public function revert($id)
    {
        $changeLog = ChangeLog::findOrFail($id);

        // Kiểm tra dữ liệu hợp lệ
        if (!$changeLog->table_name || !$changeLog->row_id || !$changeLog->old_value) {
            return redirect()->route('change-logs.index')->with('error', 'Dữ liệu lịch sử thay đổi không hợp lệ.');
        }

        // Lấy trạng thái cũ toàn bộ dòng
        $oldData = json_decode($changeLog->old_value, true);

        // Định dạng lại ngày giờ
        if (isset($oldData['created_at'])) {
            $oldData['created_at'] = Carbon::parse($oldData['created_at'])->format('Y-m-d H:i:s');
        }
        if (isset($oldData['updated_at'])) {
            $oldData['updated_at'] = Carbon::parse($oldData['updated_at'])->format('Y-m-d H:i:s');
        }

        // Kiểm tra bản ghi có tồn tại hay không
        $exists = DB::table($changeLog->table_name)
            ->where('id', $changeLog->row_id)
            ->exists();

        if ($exists) {
            // Nếu tồn tại, thực hiện cập nhật
            DB::table($changeLog->table_name)
                ->where('id', $changeLog->row_id)
                ->update($oldData);
        } else {
            // Nếu không tồn tại, thêm lại bản ghi
            DB::table($changeLog->table_name)
                ->insert($oldData);
        }

        return redirect()->route('change-logs.index')->with('success', 'Khôi phục dòng dữ liệu thành công.');
    }


    /**
     * Ghi log khi xóa dữ liệu.
     */
    public function deleted($model)
    {
        $this->logChange($model, 'delete', $model->getAttributes(), null);
    }

    /**
     * Ghi log thay đổi.
     */
    protected function logChange($model, $operationType, $oldValue = null, $newValue = null)
    {
        ChangeLog::create([
            'table_name' => $model->getTable(),
            'row_id' => $model->id,
            'old_value' => json_encode($oldValue), // Trạng thái trước thay đổi
            'new_value' => json_encode($newValue), // Trạng thái sau thay đổi
            'changed_by' => Auth::id(),
            'operation_type' => $operationType, // Loại thay đổi (create, update, delete)
            'changed_at' => now(),
        ]);
    }
}
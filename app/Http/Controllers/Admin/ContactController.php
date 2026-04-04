<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Mail;
class ContactController extends Controller
{
    public function index(Request $request)
    {

        $query = Contact::query();

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where('name', 'like', "%$keyword%")
                  ->orWhere('email', 'like', "%$keyword%")
                  ->orWhere('phone', 'like', "%$keyword%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $contacts = $query->latest()->paginate(15);

        return view('admin.contacts.index', compact('contacts'));
    }


    public function updateStatus($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->status = $contact->status == 0 ? 1 : 0;
        $contact->save();

        return redirect()->back()->with('success', 'Đã cập nhật trạng thái tin nhắn.');
    }

    // Xóa tin nhắn
    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return redirect()->back()->with('success', 'Xóa tin nhắn liên hệ thành công!');
    }
    public function reply(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);

        $request->validate([
            'email' => 'required|email',
            'subject' => 'required|string',
            'reply_content' => 'required|string',
        ]);

       try {
            Mail::html($request->reply_content, function ($message) use ($request) {
                $message->to($request->email)
                        ->subject($request->subject);
            });

            
            $contact->status = 2; 
            $contact->save();

            return redirect()->back()->with('success', 'Đã gửi email phản hồi thành công qua Gmail!');

        } catch (\Exception $e) {
            
            return redirect()->back()->with('error', 'Gửi mail thất bại! Lỗi: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Exceptions\ExceptionResponse;
use App\Http\Requests\SocietyReport\StoreSocietyReportRequest;
use App\Models\Message;
use App\Models\SocietyReport;
use App\Models\User;
use App\Services\SocietyReport\SocietyReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SocietyReportController extends Controller
{
    /**
     * @var SocietyReportService
     */
    protected SocietyReportService $societyReportService;

    /**
     * @param SocietyReportService $societyReportService
     */
    public function __construct(SocietyReportService $societyReportService)
    {
        $this->societyReportService = $societyReportService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $q = $request->get('q', false);

        try {

            $societyReports = SocietyReport::with('author', 'category', 'destination')
                ->when($q, function ($query) use ($q) {
                    $query->where('title', 'LIKE', "%$q%")->orWhere('ticket_id', 'LIKE', "%$q%");
                })
                ->orderBy('id', 'DESC')->get();

            // 
        } catch (\Exception $exception) {

            return (new ExceptionResponse($exception))->json();
        }


        return response()->json([
            'success' => true,
            'code' => 200,
            'society_reports' => $societyReports,
        ], 200)->withHeaders([
            'X-FRAME-OPTIONS' => 'ALLOW-FROM https://lapmas.netlify.app/',
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function societyReports(Request $request, string $username)
    {
        $user = User::where('username', $username)->first() ?? null;

        if (!$user || ($request->user()->username ?? null) !== $username) {

            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Not found',
            ], 404);
        }

        $societyReports = SocietyReport::where('author_id', $user->society->id)->orderBy('id', 'DESC')->get();

        return response()->json([
            'success' => true,
            'code' => 200,
            'society_reports' => $societyReports,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSocietyReportRequest $request, string $username)
    {
        /**
         * Upload attachment file.
         * 
         */

        $attachment = $request->file('attachment');

        $attachment = $attachment->storeAs(
            'attachments/' . $username,
            date('Y_m_d_H_i_s') . '.' . $attachment->getClientOriginalExtension(),
            'public'
        );

        $data = $request->except('attachment');
        $data['attachment'] = $attachment;

        try {

            $societyReport = $this->societyReportService->createReport($request->user()->society, $data);

            // 
        } catch (\Exception $exception) {

            return (new ExceptionResponse($exception))->json();
        }


        return response()->json([
            'success' => true,
            'code' => 201,
            'message' => 'Successfully created a new society report.',
            'society_report' => $societyReport,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function show(string $slug)
    {
        $societyReport = SocietyReport::where('slug', $slug)->first() ?? null;

        if (!$societyReport) {

            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Not found',
            ], 404);
        }

        $societyReport->load('author', 'category', 'destination', 'messages', 'messages.messageOrigin');

        return response()->json([
            'success' => true,
            'code' => 200,
            'society_report' => $societyReport,
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function showSocietyReport(Request $request, string $username, string $slug)
    {
        $societyReport = SocietyReport::where('slug', $slug)->first() ?? null;

        if (
            !$societyReport ||
            $societyReport->author->user->username !== $username ||
            ($request->user()->username ?? null) !== $username
        ) {

            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Not found',
            ], 404);
        }

        $societyReport->load('author', 'category', 'destination', 'messages', 'messages.messageOrigin');

        return response()->json([
            'success' => true,
            'code' => 200,
            'society_report' => $societyReport,
        ], 200);
    }

    public function sendMessage(Request $request, string $username, string $slug)
    {
        $report = SocietyReport::where('slug', $slug)->first() ?? null;

        if (
            !$report ||
            $report->author->user->username !== $username ||
            ($request->user()->username ?? null) !== $username
        ) {

            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Not found',
            ], 404);
        }

        $request->validate(['message' => ['required']]);

        $message = new Message($request->all());
        $message->societyReport()->associate($report);
        $message->messageOrigin()->associate($request->user());
        $message->messageDestination()->associate($report->destination_agency_id);
        $message->save();

        return response()->json([
            'success' => true,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  SocietyReport $societyReport
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, string $username, SocietyReport $societyReport)
    {
        if (
            !$societyReport ||
            $societyReport->author->user->username !== $username ||
            ($request->user()->username ?? null) !== $username
        ) {

            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => 'Not found',
            ], 404);
        }

        if (Storage::disk('public')->exists($societyReport->attachment)) {

            Storage::disk('public')->delete($societyReport->attachment);
        }

        $societyReport->delete();

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'Successfully deleted society report.',
        ], 200);
    }

    public function preview(Request $request)
    {
        $file = $request->get('file', false);

        return response()->file(public_path('storage/' . $file), [
            'X-Frame-Options' => 'allow-from https://lapmas.netlify.app/',
            'Content-Security-Policy' => 'frame-src https://lapmas.netlify.app/',
        ]);
    }
}

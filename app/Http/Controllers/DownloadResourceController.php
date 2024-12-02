<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class DownloadResourceController extends Controller
{
    public function curriculum_import_excel_template(){
		return Response::download(public_path("resources/template_import_excel_topics_and_activities.xlsx"));
	}

	public function topics_and_activities_import_excel_template(){
		return Response::download(public_path("resources/template_import_excel_topics_and_activities.xlsx"));
	}

	public function student_import_excel_template(){
		return Response::download(public_path("resources/template_student_import_excel.xlsx"));
	}
}

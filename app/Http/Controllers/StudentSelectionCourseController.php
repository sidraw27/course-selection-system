<?php

namespace App\Http\Controllers;

use App\Exceptions\CourseNotFoundException;
use App\Exceptions\DuplicatedException;
use App\Exceptions\StudentNotFoundException;
use App\Repositories\CourseRepository;
use App\Repositories\StudentRepository;
use App\Services\SelectionCourseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentSelectionCourseController extends Controller
{
    protected SelectionCourseService $service;

    public function __construct(SelectionCourseService $selectionCourseService)
    {
        $this->service = $selectionCourseService;
    }

    public function store(string $studentShortId, Request $request): JsonResponse
    {
        $courseShortId = $request->get('course_short_id');

        $validator = $this->validateInfo($studentShortId, $courseShortId);

        if ($validator->fails()) {
            return $this->getResponse($validator->errors()->first(), 400);
        }

        try {
            $selection = $this->service->addSelectionCourse($studentShortId, $courseShortId);
        } catch (StudentNotFoundException | CourseNotFoundException $e) {
            return $this->getResponse($e->getMessage(), 400);
        } catch (DuplicatedException $e) {
            return $this->getResponse('已選取該課程', 409);
        }

        $statusCode = (null !== $selection) ? 201 : 200;

        return $this->getResponse('ok', $statusCode);
    }

    public function destroy(string $studentShortId, string $courseShortId): JsonResponse
    {
        $validator = $this->validateInfo($studentShortId, $courseShortId);

        if ($validator->fails()) {
            return $this->getResponse($validator->errors()->first(), 400);
        }

        try {
            if ($this->service->deleteSelectionCourse($studentShortId, $courseShortId)) {
                return $this->getResponse('ok');
            } else {
                return $this->getResponse('destroy failed');
            }
        } catch (StudentNotFoundException | CourseNotFoundException $e) {
            return $this->getResponse('target not found', 404);
        }
    }

    /**
     * @param string $studentShortId
     * @param string $courseShortId
     *
     * @return array|\Illuminate\Contracts\Validation\Validator
     */
    private function validateInfo(string $studentShortId, string $courseShortId)
    {
        return Validator::make([
            'student_short_id' => $studentShortId,
            'course_short_id' => $courseShortId,
        ], [
            'student_short_id' => 'required|size:' . StudentRepository::SHORT_ID_LENGTH,
            'course_short_id' => 'required|size:' . CourseRepository::SHORT_ID_LENGTH,
        ]);
    }
}

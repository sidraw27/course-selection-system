<?php

namespace Tests\Unit\App\Services;

use App\Entities\Course;
use App\Entities\Student;
use App\Entities\Teacher;
use App\Repositories\Contracts\CourseRepositoryInterface;
use App\Repositories\Contracts\TeacherRepositoryInterface;
use App\Repositories\CourseRepository;
use App\Repositories\TeacherRepository;
use App\Services\CourseService;
use Mockery;
use Tests\TestCase;

class CourseServiceTest extends TestCase
{
    public function testGetCourseInfo()
    {
        // Arrange
        $mockCourseRepository = $this->generateMock(
            CourseRepository::class, CourseRepositoryInterface::class
        );
        $mockTeacherRepository = $this->generateMock(
            TeacherRepository::class, TeacherRepositoryInterface::class
        );
        $course = new Course();
        $course->setRawAttributes([
            'short_id' => 'EQxVKW',
            'name' => 'double_course',
            'should_exclude_attr' => false,
            'teacher' => (new Teacher())->setRawAttributes([
                'short_id' => 'XsJ1',
                'name' => 'double_teacher',
                'should_exclude_attr' => false,
            ]),
            'assistant' => (new Student())->setRawAttributes([
                'short_id' => 'LB4sWf',
                'name' => 'double_student1',
                'should_exclude_attr' => false,
            ]),
            'students' => collect([
                (new Student())->setRawAttributes([
                    'short_id' => 'LB4sWf',
                    'name' => 'double_student1',
                    'should_exclude_attr' => false,
                ]),
                (new Student())->setRawAttributes([
                    'short_id' => '7DFKPI',
                    'name' => 'double_student2',
                    'should_exclude_attr' => false,
                ])
            ])
        ]);
        $mockCourseRepository->shouldReceive('findDetailInfoCourse')
            ->andReturn($course);
        $courseService = $this->app->make(CourseService::class, [
            $mockCourseRepository, $mockTeacherRepository
        ]);
        // Act
        $actual = $courseService->getCourseInfo('test');
        // Assert
        $this->assertEquals([
            'info' => [
                'short_id' => 'EQxVKW',
                'name' => 'double_course',
            ],
            'teacher' => [
                'short_id' => 'XsJ1',
                'name' => 'double_teacher',
            ],
            'assistant' => [
                'short_id' => 'LB4sWf',
                'name' => 'double_student1',
            ],
            'students' => [
                [
                    'short_id' => 'LB4sWf',
                    'name' => 'double_student1',
                ],
                [
                    'short_id' => '7DFKPI',
                    'name' => 'double_student2',
                ]
            ]
        ], $actual);
    }

    protected function generateMock(string $class, string $interface = null)
    {
        $mock = (null === $interface) ? Mockery::mock($class) : Mockery::mock($interface);

        $this->app->instance($class, $mock);

        return $mock;
    }
}

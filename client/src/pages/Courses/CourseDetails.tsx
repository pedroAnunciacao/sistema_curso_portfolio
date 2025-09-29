import { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router';
import PageBreadcrumb from '../../components/common/PageBreadCrumb';
import PageMeta from '../../components/common/PageMeta';
import { Course } from '../../types';
import { apiService } from '../../services/api';
import { useAuth } from '../../context/AuthContext';
import Button from '../../components/ui/button/Button';
import { PlusIcon } from '../../icons';

export default function CourseDetails() {
  const { id } = useParams();
  const [course, setCourse] = useState<Course | null>(null);
  const [loading, setLoading] = useState(true);
  const { getUserRole } = useAuth();
  const userRole = getUserRole();

  useEffect(() => {
    if (id) {
      loadCourse();
    }
  }, [id]);

  const loadCourse = async () => {
    try {
      const response = await apiService.getCourse(Number(id));
      setCourse(response.data);
    } catch (error) {
      console.error('Error loading course:', error);
    } finally {
      setLoading(false);
    }
  };

  if (loading) {
    return (
      <div className="flex items-center justify-center min-h-screen">
        <div className="animate-spin rounded-full h-32 w-32 border-b-2 border-brand-500"></div>
      </div>
    );
  }

  if (!course) {
    return (
      <div className="text-center py-12">
        <p className="text-gray-500 dark:text-gray-400">Curso não encontrado.</p>
      </div>
    );
  }

  return (
    <>
      <PageMeta
        title={`${course.title} | Sistema de Vendas`}
        description={course.description}
      />
      
      <PageBreadcrumb pageTitle={course.title} />

      <div className="space-y-6">
        {/* Course Header */}
        <div className="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div className="flex flex-col lg:flex-row gap-6">
            {course.image && (
              <div className="lg:w-1/3">
                <img
                  src={course.image}
                  alt={course.title}
                  className="w-full h-64 object-cover rounded-xl"
                />
              </div>
            )}
            
            <div className="flex-1">
              <div className="flex items-start justify-between mb-4">
                <h1 className="text-2xl font-bold text-gray-800 dark:text-white/90">
                  {course.title}
                </h1>
                
                {userRole === 'student' && (
                  <Link to={`/courses/${course.id}/purchase`}>
                    <Button>Comprar Curso</Button>
                  </Link>
                )}
              </div>
              
              <p className="text-gray-600 dark:text-gray-400 mb-6">
                {course.description}
              </p>

              <div className="flex items-center gap-4">
                {(userRole === 'client' || userRole === 'teacher') && (
                  <>
                    <Link to={`/courses/${course.id}/edit`}>
                      <Button variant="outline">Editar Curso</Button>
                    </Link>
                    <Link to={`/courses/${course.id}/lessons/create`}>
                      <Button startIcon={<PlusIcon className="size-4" />}>
                        Nova Aula
                      </Button>
                    </Link>
                  </>
                )}
              </div>
            </div>
          </div>
        </div>

        {/* Lessons */}
        <div className="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div className="flex items-center justify-between mb-6">
            <h2 className="text-xl font-semibold text-gray-800 dark:text-white/90">
              Aulas ({course.lessons?.length || 0})
            </h2>
          </div>

          {course.lessons && course.lessons.length > 0 ? (
            <div className="space-y-4">
              {course.lessons.map((lesson, index) => (
                <div
                  key={lesson.id}
                  className="flex items-center justify-between p-4 border border-gray-200 rounded-lg dark:border-gray-800"
                >
                  <div className="flex items-center gap-4">
                    <div className="flex items-center justify-center w-8 h-8 bg-brand-100 rounded-full dark:bg-brand-500/20">
                      <span className="text-sm font-medium text-brand-600 dark:text-brand-400">
                        {index + 1}
                      </span>
                    </div>
                    <div>
                      <h4 className="font-medium text-gray-800 dark:text-white/90">
                        {lesson.title}
                      </h4>
                      {lesson.content && (
                        <p className="text-sm text-gray-500 dark:text-gray-400 line-clamp-2">
                          {lesson.content}
                        </p>
                      )}
                    </div>
                  </div>

                  {(userRole === 'client' || userRole === 'teacher') && (
                    <div className="flex gap-2">
                      <Link to={`/lessons/${lesson.id}/edit`}>
                        <Button size="sm" variant="outline">
                          Editar
                        </Button>
                      </Link>
                    </div>
                  )}
                </div>
              ))}
            </div>
          ) : (
            <div className="text-center py-8">
              <p className="text-gray-500 dark:text-gray-400">
                Nenhuma aula cadastrada ainda.
              </p>
              {(userRole === 'client' || userRole === 'teacher') && (
                <Link to={`/courses/${course.id}/lessons/create`} className="mt-4 inline-block">
                  <Button startIcon={<PlusIcon className="size-4" />}>
                    Criar Primeira Aula
                  </Button>
                </Link>
              )}
            </div>
          )}
        </div>

        {/* Students (for teachers and clients) */}
        {(userRole === 'client' || userRole === 'teacher') && course.students && (
          <div className="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h2 className="text-xl font-semibold text-gray-800 dark:text-white/90 mb-6">
              Alunos Matriculados ({course.students.length})
            </h2>

            {course.students.length > 0 ? (
              <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                {course.students.map((student) => (
                  <div
                    key={student.id}
                    className="flex items-center gap-3 p-4 border border-gray-200 rounded-lg dark:border-gray-800"
                  >
                    <div className="w-10 h-10 bg-brand-100 rounded-full flex items-center justify-center dark:bg-brand-500/20">
                      <span className="text-sm font-medium text-brand-600 dark:text-brand-400">
                        {student.name.charAt(0).toUpperCase()}
                      </span>
                    </div>
                    <div>
                      <p className="font-medium text-gray-800 dark:text-white/90">
                        {student.name}
                      </p>
                    </div>
                  </div>
                ))}
              </div>
            ) : (
              <p className="text-gray-500 dark:text-gray-400">
                Nenhum aluno matriculado ainda.
              </p>
            )}
          </div>
        )}
      </div>
    </>
  );
}
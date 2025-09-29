import { useState, useEffect } from 'react';
import { Link } from 'react-router';
import PageBreadcrumb from '../../components/common/PageBreadCrumb';
import PageMeta from '../../components/common/PageMeta';
import { Course } from '../../types';
import { apiService } from '../../services/api';
import { useAuth } from '../../context/AuthContext';
import Button from '../../components/ui/button/Button';
import { PlusIcon } from '../../icons';

export default function CourseList() {
  const [courses, setCourses] = useState<Course[]>([]);
  const [loading, setLoading] = useState(true);
  const { getUserRole, user } = useAuth();
  const userRole = getUserRole();

  useEffect(() => {
    loadCourses();
  }, []);

  const loadCourses = async () => {
    try {
      setLoading(true);
      let response;
      
      if (userRole === 'teacher' && user?.person?.teacher) {
        response = await apiService.getCoursesByTeacher(user.person.teacher.id);
      } else {
        response = await apiService.getCourses();
      }
      
      setCourses(response.data.data || []);
    } catch (error) {
      console.error('Error loading courses:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleDelete = async (id: number) => {
    if (window.confirm('Tem certeza que deseja excluir este curso?')) {
      try {
        await apiService.deleteCourse(id);
        loadCourses();
      } catch (error) {
        console.error('Error deleting course:', error);
      }
    }
  };

  if (loading) {
    return (
      <div className="flex items-center justify-center min-h-screen">
        <div className="animate-spin rounded-full h-32 w-32 border-b-2 border-brand-500"></div>
      </div>
    );
  }

  return (
    <>
      <PageMeta
        title="Cursos | Sistema de Vendas"
        description="Lista de cursos disponíveis"
      />
      
      <div className="flex items-center justify-between mb-6">
        <PageBreadcrumb pageTitle="Cursos" />
        {(userRole === 'client' || userRole === 'teacher') && (
          <Link to="/courses/create">
            <Button startIcon={<PlusIcon className="size-4" />}>
              Novo Curso
            </Button>
          </Link>
        )}
      </div>

      <div className="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        {courses.map((course) => (
          <div
            key={course.id}
            className="overflow-hidden bg-white border border-gray-200 rounded-2xl dark:border-gray-800 dark:bg-white/[0.03]"
          >
            {course.image && (
              <div className="h-48 overflow-hidden">
                <img
                  src={course.image}
                  alt={course.title}
                  className="object-cover w-full h-full"
                />
              </div>
            )}
            
            <div className="p-6">
              <h3 className="mb-2 text-lg font-semibold text-gray-800 dark:text-white/90">
                {course.title}
              </h3>
              
              <p className="mb-4 text-sm text-gray-500 dark:text-gray-400 line-clamp-3">
                {course.description}
              </p>

              {course.teacher && (
                <p className="mb-4 text-xs text-gray-400">
                  Professor: {course.teacher.id}
                </p>
              )}

              <div className="flex items-center justify-between">
                <div className="flex gap-2">
                  <Link to={`/courses/${course.id}`}>
                    <Button size="sm" variant="outline">
                      Ver Detalhes
                    </Button>
                  </Link>
                  
                  {userRole === 'student' && (
                    <Link to={`/courses/${course.id}/purchase`}>
                      <Button size="sm">
                        Comprar
                      </Button>
                    </Link>
                  )}
                </div>

                {(userRole === 'client' || userRole === 'teacher') && (
                  <div className="flex gap-2">
                    <Link to={`/courses/${course.id}/edit`}>
                      <Button size="sm" variant="outline">
                        Editar
                      </Button>
                    </Link>
                    <Button
                      size="sm"
                      variant="outline"
                      onClick={() => handleDelete(course.id)}
                      className="text-error-500 hover:text-error-600"
                    >
                      Excluir
                    </Button>
                  </div>
                )}
              </div>
            </div>
          </div>
        ))}
      </div>

      {courses.length === 0 && (
        <div className="text-center py-12">
          <p className="text-gray-500 dark:text-gray-400">
            Nenhum curso encontrado.
          </p>
        </div>
      )}
    </>
  );
}
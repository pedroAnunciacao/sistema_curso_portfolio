import { useState, useEffect } from 'react';
import { Link } from 'react-router';
import PageBreadcrumb from '../../components/common/PageBreadCrumb';
import PageMeta from '../../components/common/PageMeta';
import { Course, Teacher } from '../../types';
import { apiService } from '../../services/api';
import { useAuth } from '../../context/AuthContext';
import Button from '../../components/ui/button/Button';
import { PlusIcon } from '../../icons';
import {
  Table,
  TableBody,
  TableCell,
  TableHeader,
  TableRow,
} from '../../components/ui/table';
import Badge from '../../components/ui/badge/Badge';
import Select from '../../components/form/Select';

export default function CourseList() {
  const [courses, setCourses] = useState<Course[]>([]);
  const [teachers, setTeachers] = useState<Teacher[]>([]);
  const [loading, setLoading] = useState(true);
  const [selectedTeacher, setSelectedTeacher] = useState<string>('');
  const { getUserRole, user } = useAuth();
  const userRole = getUserRole();

  useEffect(() => {
    loadInitialData();
  }, []);

  useEffect(() => {
    if (selectedTeacher) {
      loadCoursesByTeacher();
    } else {
      loadCourses();
    }
  }, [selectedTeacher]);

  const loadInitialData = async () => {
    try {
      setLoading(true);
      
      // Carregar professores para o select (apenas para alunos)
      if (userRole === 'student') {
        const teachersResponse = await apiService.getTeachers();
        setTeachers(teachersResponse.data || []);
      }
      
      await loadCourses();
    } catch (error) {
      console.error('Error loading initial data:', error);
    } finally {
      setLoading(false);
    }
  };

  const loadCourses = async () => {
    try {
      let response;
      
      if (userRole === 'teacher' && user?.person?.teacher) {
        response = await apiService.getCoursesByTeacher(user.person.teacher.id);
      } else {
        response = await apiService.getCourses();
      }
      
      setCourses(response.data || []);
    } catch (error) {
      console.error('Error loading courses:', error);
    }
  };

  const loadCoursesByTeacher = async () => {
    try {
      const response = await apiService.getCoursesByTeacher(Number(selectedTeacher));
      setCourses(response.data || []);
    } catch (error) {
      console.error('Error loading courses by teacher:', error);
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

      {/* Teacher Filter for Students */}
      {userRole === 'student' && (
        <div className="mb-6 bg-white rounded-2xl border border-gray-200 p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <div className="max-w-xs">
            <label className="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">
              Filtrar por Professor
            </label>
            <Select
              options={[
                { value: '', label: 'Todos os professores' },
                ...teachers.map(teacher => ({
                  value: teacher.id.toString(),
                  label: teacher.name
                }))
              ]}
              placeholder="Selecione um professor"
              onChange={setSelectedTeacher}
              defaultValue={selectedTeacher}
            />
          </div>
        </div>
      )}

      <div className="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div className="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
          <h3 className="text-lg font-semibold text-gray-800 dark:text-white/90">
            Lista de Cursos
          </h3>
        </div>
        
        <div className="max-w-full overflow-x-auto">
          <Table>
            <TableHeader className="border-b border-gray-100 dark:border-gray-800">
              <TableRow>
                <TableCell
                  isHeader
                  className="px-6 py-4 font-medium text-gray-500 text-start text-sm dark:text-gray-400"
                >
                  Curso
                </TableCell>
                <TableCell
                  isHeader
                  className="px-6 py-4 font-medium text-gray-500 text-start text-sm dark:text-gray-400"
                >
                  Professor
                </TableCell>
                <TableCell
                  isHeader
                  className="px-6 py-4 font-medium text-gray-500 text-start text-sm dark:text-gray-400"
                >
                  Preço
                </TableCell>
                <TableCell
                  isHeader
                  className="px-6 py-4 font-medium text-gray-500 text-start text-sm dark:text-gray-400"
                >
                  Aulas
                </TableCell>
                <TableCell
                  isHeader
                  className="px-6 py-4 font-medium text-gray-500 text-start text-sm dark:text-gray-400"
                >
                  Data de Criação
                </TableCell>
                <TableCell
                  isHeader
                  className="px-6 py-4 font-medium text-gray-500 text-start text-sm dark:text-gray-400"
                >
                  Ações
                </TableCell>
              </TableRow>
            </TableHeader>

            <TableBody className="divide-y divide-gray-100 dark:divide-gray-800">
              {courses.map((course) => (
                <TableRow key={course.id}>
                  <TableCell className="px-6 py-4">
                    <div className="flex items-center gap-3">
                      {course.image && (
                        <div className="w-12 h-12 overflow-hidden rounded-lg">
                          <img
                            src={course.image}
                            alt={course.title}
                            className="w-full h-full object-cover"
                          />
                        </div>
                      )}
                      <div>
                        <span className="font-medium text-gray-800 dark:text-white/90">
                          {course.title}
                        </span>
                        <p className="text-sm text-gray-500 dark:text-gray-400 line-clamp-1">
                          {course.description}
                        </p>
                      </div>
                    </div>
                  </TableCell>
                  
                  <TableCell className="px-6 py-4 text-gray-500 dark:text-gray-400">
                    {course.teacher?.name || 'N/A'}
                  </TableCell>
                  
                  <TableCell className="px-6 py-4">
                    <span className="font-medium text-brand-600 dark:text-brand-400">
                      R$ {course.price || '0,00'}
                    </span>
                  </TableCell>
                  
                  <TableCell className="px-6 py-4 text-gray-500 dark:text-gray-400">
                    {course.lessons?.length || 0} aulas
                  </TableCell>
                  
                  <TableCell className="px-6 py-4 text-gray-500 dark:text-gray-400">
                    {new Date(course.created_at).toLocaleDateString('pt-BR')}
                  </TableCell>
                  
                  <TableCell className="px-6 py-4">
                    <div className="flex gap-2">
                      <Link to={`/courses/${course.id}`}>
                        <Button size="sm" variant="outline">
                          Ver
                        </Button>
                      </Link>
                      
                      {userRole === 'student' && (
                        <Link to={`/courses/${course.id}/purchase`}>
                          <Button size="sm">
                            Comprar
                          </Button>
                        </Link>
                      )}

                      {(userRole === 'client' || userRole === 'teacher') && (
                        <>
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
                        </>
                      )}
                    </div>
                  </TableCell>
                </TableRow>
              ))}
            </TableBody>
          </Table>
        </div>
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
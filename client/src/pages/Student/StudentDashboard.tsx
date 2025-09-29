import { useState, useEffect } from 'react';
import { Link } from 'react-router';
import PageMeta from '../../components/common/PageMeta';
import { Course, Enrollment, Checkout } from '../../types';
import { apiService } from '../../services/api';
import { useAuth } from '../../context/AuthContext';
import Button from '../../components/ui/button/Button';
import Badge from '../../components/ui/badge/Badge';

export default function StudentDashboard() {
  const [availableCourses, setAvailableCourses] = useState<Course[]>([]);
  const [enrollments, setEnrollments] = useState<Enrollment[]>([]);
  const [purchases, setPurchases] = useState<Checkout[]>([]);
  const [loading, setLoading] = useState(true);
  const { user } = useAuth();

  useEffect(() => {
    loadData();
  }, []);

  const loadData = async () => {
    try {
      setLoading(true);
      
      // Carregar cursos disponíveis
      const coursesResponse = await apiService.getCourses();
      setAvailableCourses(coursesResponse.data.data || []);
      
      // Carregar matrículas
      const enrollmentsResponse = await apiService.getEnrollments();
      setEnrollments(enrollmentsResponse.data.data || []);
      
    } catch (error) {
      console.error('Error loading data:', error);
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

  return (
    <>
      <PageMeta
        title="Portal do Aluno | Sistema de Vendas"
        description="Portal do estudante para comprar e estudar cursos"
      />
      
      {/* Hero Section */}
      <div className="relative overflow-hidden rounded-3xl bg-gradient-to-r from-brand-500 to-brand-600 p-8 text-white mb-8">
        <div className="relative z-10">
          <h1 className="text-3xl font-bold mb-2">
            Bem-vindo, {user?.person?.name}!
          </h1>
          <p className="text-brand-100 text-lg">
            Explore nossos cursos e continue sua jornada de aprendizado
          </p>
        </div>
        <div className="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-32 translate-x-32"></div>
        <div className="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-24 -translate-x-24"></div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-4 gap-6">
        {/* Stats Cards */}
        <div className="lg:col-span-4 grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
          <div className="bg-white rounded-2xl border border-gray-200 p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div className="flex items-center gap-4">
              <div className="w-12 h-12 bg-brand-100 rounded-xl flex items-center justify-center dark:bg-brand-500/20">
                <svg className="w-6 h-6 text-brand-600 dark:text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
              </div>
              <div>
                <p className="text-sm text-gray-500 dark:text-gray-400">Cursos Matriculados</p>
                <p className="text-2xl font-bold text-gray-800 dark:text-white/90">
                  {enrollments.length}
                </p>
              </div>
            </div>
          </div>

          <div className="bg-white rounded-2xl border border-gray-200 p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div className="flex items-center gap-4">
              <div className="w-12 h-12 bg-success-100 rounded-xl flex items-center justify-center dark:bg-success-500/20">
                <svg className="w-6 h-6 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div>
                <p className="text-sm text-gray-500 dark:text-gray-400">Cursos Concluídos</p>
                <p className="text-2xl font-bold text-gray-800 dark:text-white/90">0</p>
              </div>
            </div>
          </div>

          <div className="bg-white rounded-2xl border border-gray-200 p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div className="flex items-center gap-4">
              <div className="w-12 h-12 bg-warning-100 rounded-xl flex items-center justify-center dark:bg-warning-500/20">
                <svg className="w-6 h-6 text-warning-600 dark:text-warning-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                </svg>
              </div>
              <div>
                <p className="text-sm text-gray-500 dark:text-gray-400">Total Investido</p>
                <p className="text-2xl font-bold text-gray-800 dark:text-white/90">R$ 0,00</p>
              </div>
            </div>
          </div>
        </div>

        {/* Available Courses */}
        <div className="lg:col-span-3">
          <div className="bg-white rounded-2xl border border-gray-200 p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h2 className="text-xl font-semibold text-gray-800 dark:text-white/90 mb-6">
              Cursos Disponíveis
            </h2>
            
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
              {availableCourses.slice(0, 6).map((course) => (
                <div
                  key={course.id}
                  className="border border-gray-200 rounded-xl p-4 hover:shadow-md transition-shadow dark:border-gray-800"
                >
                  {course.image && (
                    <img
                      src={course.image}
                      alt={course.title}
                      className="w-full h-32 object-cover rounded-lg mb-4"
                    />
                  )}
                  
                  <h3 className="font-semibold text-gray-800 dark:text-white/90 mb-2">
                    {course.title}
                  </h3>
                  
                  <p className="text-sm text-gray-500 dark:text-gray-400 mb-4 line-clamp-2">
                    {course.description}
                  </p>
                  
                  <div className="flex gap-2">
                    <Link to={`/courses/${course.id}`} className="flex-1">
                      <Button size="sm" variant="outline" className="w-full">
                        Ver Detalhes
                      </Button>
                    </Link>
                    <Link to={`/courses/${course.id}/purchase`} className="flex-1">
                      <Button size="sm" className="w-full">
                        Comprar
                      </Button>
                    </Link>
                  </div>
                </div>
              ))}
            </div>

            {availableCourses.length > 6 && (
              <div className="text-center mt-6">
                <Link to="/courses">
                  <Button variant="outline">Ver Todos os Cursos</Button>
                </Link>
              </div>
            )}
          </div>
        </div>

        {/* My Courses */}
        <div className="lg:col-span-1">
          <div className="bg-white rounded-2xl border border-gray-200 p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h2 className="text-lg font-semibold text-gray-800 dark:text-white/90 mb-6">
              Meus Cursos
            </h2>
            
            {enrollments.length > 0 ? (
              <div className="space-y-4">
                {enrollments.map((enrollment) => (
                  <div
                    key={enrollment.id}
                    className="border border-gray-200 rounded-lg p-4 dark:border-gray-800"
                  >
                    <h4 className="font-medium text-gray-800 dark:text-white/90 mb-2">
                      {enrollment.course?.title}
                    </h4>
                    <div className="flex items-center justify-between">
                      <Badge color="success" size="sm">
                        Matriculado
                      </Badge>
                      <Link to={`/courses/${enrollment.course?.id}`}>
                        <Button size="sm" variant="outline">
                          Estudar
                        </Button>
                      </Link>
                    </div>
                  </div>
                ))}
              </div>
            ) : (
              <div className="text-center py-8">
                <p className="text-gray-500 dark:text-gray-400 mb-4">
                  Você ainda não está matriculado em nenhum curso.
                </p>
                <Link to="/courses">
                  <Button size="sm">
                    Explorar Cursos
                  </Button>
                </Link>
              </div>
            )}
          </div>
        </div>
      </div>
    </>
  );
}
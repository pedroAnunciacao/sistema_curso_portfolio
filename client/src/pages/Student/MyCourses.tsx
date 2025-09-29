import { useState, useEffect } from 'react';
import { Link } from 'react-router';
import PageMeta from '../../components/common/PageMeta';
import { Enrollment } from '../../types';
import { apiService } from '../../services/api';
import Button from '../../components/ui/button/Button';
import Badge from '../../components/ui/badge/Badge';

export default function MyCourses() {
  const [enrollments, setEnrollments] = useState<Enrollment[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    loadEnrollments();
  }, []);

  const loadEnrollments = async () => {
    try {
      const response = await apiService.getEnrollments();
      setEnrollments(response.data.data || []);
    } catch (error) {
      console.error('Error loading enrollments:', error);
    } finally {
      setLoading(false);
    }
  };

  if (loading) {
    return (
      <div className="flex items-center justify-center min-h-96">
        <div className="animate-spin rounded-full h-32 w-32 border-b-2 border-brand-500"></div>
      </div>
    );
  }

  return (
    <>
      <PageMeta
        title="Meus Cursos | Portal do Aluno"
        description="Seus cursos matriculados"
      />
      
      <div className="mb-8">
        <h1 className="text-3xl font-bold text-gray-800 dark:text-white/90 mb-2">
          Meus Cursos
        </h1>
        <p className="text-gray-600 dark:text-gray-400">
          Continue seus estudos nos cursos que você está matriculado
        </p>
      </div>

      {enrollments.length > 0 ? (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {enrollments.map((enrollment) => (
            <div
              key={enrollment.id}
              className="bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow dark:border-gray-800 dark:bg-white/[0.03]"
            >
              {enrollment.course?.image && (
                <div className="h-48 overflow-hidden">
                  <img
                    src={enrollment.course.image}
                    alt={enrollment.course.title}
                    className="w-full h-full object-cover"
                  />
                </div>
              )}
              
              <div className="p-6">
                <div className="flex items-start justify-between mb-3">
                  <h3 className="text-lg font-semibold text-gray-800 dark:text-white/90">
                    {enrollment.course?.title}
                  </h3>
                  <Badge color="success" size="sm">
                    Matriculado
                  </Badge>
                </div>
                
                <p className="text-sm text-gray-500 dark:text-gray-400 mb-4 line-clamp-2">
                  {enrollment.course?.description}
                </p>

                <div className="flex items-center justify-between mb-4">
                  <div className="flex items-center gap-2">
                    <span className="text-xs text-gray-400">
                      {enrollment.course?.lessons?.length || 0} aulas
                    </span>
                  </div>
                  <div className="text-xs text-gray-400">
                    Matriculado em {new Date(enrollment.created_at).toLocaleDateString('pt-BR')}
                  </div>
                </div>

                {/* Progress Bar */}
                <div className="mb-4">
                  <div className="flex items-center justify-between mb-2">
                    <span className="text-xs text-gray-500 dark:text-gray-400">Progresso</span>
                    <span className="text-xs text-gray-500 dark:text-gray-400">0%</span>
                  </div>
                  <div className="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                    <div className="bg-brand-500 h-2 rounded-full" style={{ width: '0%' }}></div>
                  </div>
                </div>

                <Link to={`/courses/${enrollment.course?.id}`}>
                  <Button className="w-full">
                    Continuar Estudando
                  </Button>
                </Link>
              </div>
            </div>
          ))}
        </div>
      ) : (
        <div className="text-center py-16">
          <div className="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6 dark:bg-gray-800">
            <svg className="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
          </div>
          <h3 className="text-xl font-semibold text-gray-800 dark:text-white/90 mb-2">
            Você ainda não tem cursos
          </h3>
          <p className="text-gray-500 dark:text-gray-400 mb-6">
            Explore nossa biblioteca de cursos e comece sua jornada de aprendizado
          </p>
          <Link to="/student/courses">
            <Button>
              Explorar Cursos
            </Button>
          </Link>
        </div>
      )}
    </>
  );
}
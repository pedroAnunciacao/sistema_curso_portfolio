import { useState, useEffect } from 'react';
import { Link } from 'react-router';
import PageMeta from '../../components/common/PageMeta';
import { Course } from '../../types';
import { apiService } from '../../services/api';
import Button from '../../components/ui/button/Button';
import Input from '../../components/form/input/InputField';

export default function StudentCourses() {
  const [courses, setCourses] = useState<Course[]>([]);
  const [filteredCourses, setFilteredCourses] = useState<Course[]>([]);
  const [loading, setLoading] = useState(true);
  const [searchTerm, setSearchTerm] = useState('');

  useEffect(() => {
    loadCourses();
  }, []);

  useEffect(() => {
    if (searchTerm) {
      const filtered = courses.filter(course =>
        course.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
        course.description.toLowerCase().includes(searchTerm.toLowerCase())
      );
      setFilteredCourses(filtered);
    } else {
      setFilteredCourses(courses);
    }
  }, [searchTerm, courses]);

  const loadCourses = async () => {
    try {
      const response = await apiService.getCourses();
      setCourses(response.data.data || []);
      setFilteredCourses(response.data.data || []);
    } catch (error) {
      console.error('Error loading courses:', error);
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
        title="Cursos Disponíveis | Portal do Aluno"
        description="Explore todos os cursos disponíveis"
      />
      
      <div className="mb-8">
        <h1 className="text-3xl font-bold text-gray-800 dark:text-white/90 mb-2">
          Cursos Disponíveis
        </h1>
        <p className="text-gray-600 dark:text-gray-400">
          Explore nossa biblioteca de cursos e encontre o conhecimento que você precisa
        </p>
      </div>

      {/* Search */}
      <div className="mb-8">
        <div className="max-w-md">
          <Input
            type="text"
            placeholder="Buscar cursos..."
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
          />
        </div>
      </div>

      {/* Courses Grid */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {filteredCourses.map((course) => (
          <div
            key={course.id}
            className="bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow dark:border-gray-800 dark:bg-white/[0.03]"
          >
            {course.image && (
              <div className="h-48 overflow-hidden">
                <img
                  src={course.image}
                  alt={course.title}
                  className="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                />
              </div>
            )}
            
            <div className="p-6">
              <h3 className="text-lg font-semibold text-gray-800 dark:text-white/90 mb-2">
                {course.title}
              </h3>
              
              <p className="text-sm text-gray-500 dark:text-gray-400 mb-4 line-clamp-3">
                {course.description}
              </p>

              <div className="flex items-center justify-between mb-4">
                <div className="flex items-center gap-2">
                  <span className="text-xs text-gray-400">
                    {course.lessons?.length || 0} aulas
                  </span>
                </div>
                <div className="text-lg font-bold text-brand-600 dark:text-brand-400">
                  R$ 199,90
                </div>
              </div>

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
          </div>
        ))}
      </div>

      {filteredCourses.length === 0 && (
        <div className="text-center py-12">
          <div className="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 dark:bg-gray-800">
            <svg className="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
          </div>
          <p className="text-gray-500 dark:text-gray-400">
            {searchTerm ? 'Nenhum curso encontrado para sua busca.' : 'Nenhum curso disponível.'}
          </p>
        </div>
      )}
    </>
  );
}
import { useState, useEffect } from 'react';
import { useNavigate, useParams } from 'react-router';
import PageBreadcrumb from '../../components/common/PageBreadCrumb';
import PageMeta from '../../components/common/PageMeta';
import { Lesson } from '../../types';
import { apiService } from '../../services/api';
import Button from '../../components/ui/button/Button';
import Input from '../../components/form/input/InputField';
import TextArea from '../../components/form/input/TextArea';
import Label from '../../components/form/Label';

export default function LessonForm() {
  const { courseId, id } = useParams();
  const navigate = useNavigate();
  const isEdit = Boolean(id);
  
  const [formData, setFormData] = useState({
    title: '',
    content: '',
    course_id: Number(courseId),
    image: '',
    link_youtube: ''
  });
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    if (isEdit && id) {
      loadLesson();
    }
  }, [id, isEdit]);

  const loadLesson = async () => {
    try {
      const response = await apiService.getLesson(Number(id));
      const lesson = response.data;
      setFormData({
        title: lesson.title,
        content: lesson.content,
        course_id: lesson.course_id || Number(courseId),
        image: lesson.image || '',
        link_youtube: lesson.link_youtube || ''
      });
    } catch (error) {
      console.error('Error loading lesson:', error);
    }
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);

    try {
      const lessonData = { ...formData };
      if (isEdit) {
        lessonData.id = Number(id);
        await apiService.updateLesson(lessonData);
      } else {
        await apiService.createLesson(lessonData);
      }
      
      navigate(`/courses/${courseId}`);
    } catch (error) {
      console.error('Error saving lesson:', error);
    } finally {
      setLoading(false);
    }
  };

  return (
    <>
      <PageMeta
        title={`${isEdit ? 'Editar' : 'Criar'} Aula | Sistema de Vendas`}
        description={`${isEdit ? 'Editar' : 'Criar'} aula`}
      />
      
      <PageBreadcrumb pageTitle={`${isEdit ? 'Editar' : 'Criar'} Aula`} />

      <div className="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
        <form onSubmit={handleSubmit} className="space-y-6">
          <div>
            <Label htmlFor="title">Título da Aula</Label>
            <Input
              id="title"
              type="text"
              value={formData.title}
              onChange={(e) => setFormData(prev => ({ ...prev, title: e.target.value }))}
              placeholder="Digite o título da aula"
              required
            />
          </div>

          <div>
            <Label htmlFor="content">Conteúdo</Label>
            <TextArea
              value={formData.content}
              onChange={(value) => setFormData(prev => ({ ...prev, content: value }))}
              placeholder="Digite o conteúdo da aula"
              rows={6}
            />
          </div>

          <div>
            <Label htmlFor="link_youtube">Link do YouTube (opcional)</Label>
            <Input
              id="link_youtube"
              type="url"
              value={formData.link_youtube}
              onChange={(e) => setFormData(prev => ({ ...prev, link_youtube: e.target.value }))}
              placeholder="https://youtube.com/watch?v=..."
            />
          </div>

          <div className="flex gap-4">
            <Button
              type="button"
              variant="outline"
              onClick={() => navigate(`/courses/${courseId}`)}
            >
              Cancelar
            </Button>
            <Button type="submit" disabled={loading}>
              {loading ? 'Salvando...' : isEdit ? 'Atualizar' : 'Criar'}
            </Button>
          </div>
        </form>
      </div>
    </>
  );
}
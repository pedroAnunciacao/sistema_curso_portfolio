import { useState, useEffect } from 'react';
import { useNavigate, useParams } from 'react-router';
import PageBreadcrumb from '../../components/common/PageBreadCrumb';
import PageMeta from '../../components/common/PageMeta';
import { Course } from '../../types';
import { apiService } from '../../services/api';
import Button from '../../components/ui/button/Button';
import Input from '../../components/form/input/InputField';
import TextArea from '../../components/form/input/TextArea';
import Label from '../../components/form/Label';

export default function CourseForm() {
  const { id } = useParams();
  const navigate = useNavigate();
  const isEdit = Boolean(id);
  
  const [formData, setFormData] = useState({
    title: '',
    description: '',
    image: '',
    price: ''
  });
  const [loading, setLoading] = useState(false);
  const [imagePreview, setImagePreview] = useState<string>('');

  useEffect(() => {
    if (isEdit && id) {
      loadCourse();
    }
  }, [id, isEdit]);

  const loadCourse = async () => {
    try {
      const response = await apiService.getCourse(Number(id));
      const course = response.data;
      setFormData({
        title: course.title,
        description: course.description,
        image: course.image || '',
        price: course.price || ''
      });
      if (course.image) {
        setImagePreview(course.image);
      }
    } catch (error) {
      console.error('Error loading course:', error);
    }
  };

  const handleImageChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = (e) => {
        const base64 = e.target?.result as string;
        setFormData(prev => ({ ...prev, image: base64 }));
        setImagePreview(base64);
      };
      reader.readAsDataURL(file);
    }
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);

    try {
      const courseData = { ...formData };
      if (isEdit) {
        courseData.id = Number(id);
        await apiService.updateCourse(courseData);
      } else {
        await apiService.createCourse(courseData);
      }
      
      navigate('/courses');
    } catch (error) {
      console.error('Error saving course:', error);
    } finally {
      setLoading(false);
    }
  };

  return (
    <>
      <PageMeta
        title={`${isEdit ? 'Editar' : 'Criar'} Curso | Sistema de Vendas`}
        description={`${isEdit ? 'Editar' : 'Criar'} curso`}
      />
      
      <PageBreadcrumb pageTitle={`${isEdit ? 'Editar' : 'Criar'} Curso`} />

      <div className="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
        <form onSubmit={handleSubmit} className="space-y-6">
          <div>
            <Label htmlFor="title">Título do Curso</Label>
            <Input
              id="title"
              type="text"
              value={formData.title}
              onChange={(e) => setFormData(prev => ({ ...prev, title: e.target.value }))}
              placeholder="Digite o título do curso"
              required
            />
          </div>

          <div>
            <Label htmlFor="description">Descrição</Label>
            <TextArea
              value={formData.description}
              onChange={(value) => setFormData(prev => ({ ...prev, description: value }))}
              placeholder="Digite a descrição do curso"
              rows={4}
            />
          </div>

          <div>
            <Label htmlFor="price">Preço (R$)</Label>
            <Input
              id="price"
              type="number"
              step="0.01"
              min="0"
              value={formData.price}
              onChange={(e) => setFormData(prev => ({ ...prev, price: e.target.value }))}
              placeholder="0,00"
            />
          </div>

          <div>
            <Label htmlFor="image">Imagem do Curso</Label>
            <input
              id="image"
              type="file"
              accept="image/*"
              onChange={handleImageChange}
              className="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm shadow-theme-xs focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:focus:border-brand-800"
            />
            {imagePreview && (
              <div className="mt-4">
                <img
                  src={imagePreview}
                  alt="Preview"
                  className="h-32 w-48 object-cover rounded-lg border border-gray-200 dark:border-gray-800"
                />
              </div>
            )}
          </div>

          <div className="flex gap-4">
            <Button
              type="button"
              variant="outline"
              onClick={() => navigate('/courses')}
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
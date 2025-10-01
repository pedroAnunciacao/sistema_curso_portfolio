import { useState, useEffect } from 'react';
import PageBreadcrumb from '../../components/common/PageBreadCrumb';
import PageMeta from '../../components/common/PageMeta';
import { Checkout, Teacher, Student } from '../../types';
import { apiService } from '../../services/api';
import { useAuth } from '../../context/AuthContext';
import {
  Table,
  TableBody,
  TableCell,
  TableHeader,
  TableRow,
} from '../../components/ui/table';
import Badge from '../../components/ui/badge/Badge';
import Select from '../../components/form/Select';
import Label from '../../components/form/Label';

export default function CheckoutList() {
  const [checkouts, setCheckouts] = useState<Checkout[]>([]);
  const [teachers, setTeachers] = useState<Teacher[]>([]);
  const [students, setStudents] = useState<Student[]>([]);
  const [loading, setLoading] = useState(true);
  const [filters, setFilters] = useState({
    teacher_id: '',
    student_id: ''
  });
  const { getUserRole } = useAuth();
  const userRole = getUserRole();

  useEffect(() => {
    loadInitialData();
  }, []);

  useEffect(() => {
    loadCheckouts();
  }, [filters]);

  const loadInitialData = async () => {
    try {
      setLoading(true);
      
      // Carregar professores e alunos para filtros (baseado no role)
      if (userRole === 'client') {
        const [teachersResponse, studentsResponse] = await Promise.all([
          apiService.getTeachers(),
          apiService.getStudents()
        ]);
        setTeachers(teachersResponse.data || []);
        setStudents(studentsResponse.data || []);
      } else if (userRole === 'teacher') {
        const studentsResponse = await apiService.getStudents();
        setStudents(studentsResponse.data || []);
      }
      
      await loadCheckouts();
    } catch (error) {
      console.error('Error loading initial data:', error);
    } finally {
      setLoading(false);
    }
  };

  const loadCheckouts = async () => {
    try {
      const queryParams = Object.fromEntries(
        Object.entries(filters).filter(([_, value]) => value !== '')
      );
      
      const response = await apiService.getCheckouts(queryParams);
      setCheckouts(response.data || []);
    } catch (error) {
      console.error('Error loading checkouts:', error);
    }
  };

  const getStatusColor = (status: string): 'success' | 'warning' | 'error' => {
    switch (status.toLowerCase()) {
      case 'approved':
      case 'paid':
        return 'success';
      case 'pending':
        return 'warning';
      case 'cancelled':
      case 'rejected':
        return 'error';
      default:
        return 'warning';
    }
  };

  const getStatusText = (status: string) => {
    switch (status.toLowerCase()) {
      case 'approved':
        return 'Aprovado';
      case 'pending':
        return 'Pendente';
      case 'cancelled':
        return 'Cancelado';
      case 'rejected':
        return 'Rejeitado';
      default:
        return status;
    }
  };

  const getMethodText = (method: string) => {
    switch (method.toLowerCase()) {
      case 'pix':
        return 'PIX';
      case 'card':
        return 'Cartão';
      case 'boleto':
        return 'Boleto';
      default:
        return method;
    }
  };

  const getPaymentAmount = (checkout: Checkout) => {
    if (checkout.mercado_pago?.transaction_amount) {
      return `R$ ${checkout.mercado_pago.transaction_amount.toFixed(2).replace('.', ',')}`;
    }
    return 'N/A';
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
        title="Vendas | Sistema de Vendas"
        description="Histórico de vendas e pagamentos"
      />
      
      <PageBreadcrumb pageTitle="Vendas" />

      {/* Filters */}
      {(userRole === 'client' || userRole === 'teacher') && (
        <div className="mb-6 bg-white rounded-2xl border border-gray-200 p-6 dark:border-gray-800 dark:bg-white/[0.03]">
          <h3 className="text-lg font-semibold text-gray-800 dark:text-white/90 mb-4">
            Filtros
          </h3>
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            {userRole === 'client' && (
              <div>
                <Label>Professor</Label>
                <Select
                  options={[
                    { value: '', label: 'Todos os professores' },
                    ...teachers.map(teacher => ({
                      value: teacher.id.toString(),
                      label: teacher.name
                    }))
                  ]}
                  placeholder="Selecione um professor"
                  onChange={(value) => setFilters(prev => ({ ...prev, teacher_id: value }))}
                  defaultValue={filters.teacher_id}
                />
              </div>
            )}
            
            {(userRole === 'client' || userRole === 'teacher') && (
              <div>
                <Label>Aluno</Label>
                <Select
                  options={[
                    { value: '', label: 'Todos os alunos' },
                    ...students.map(student => ({
                      value: student.id.toString(),
                      label: student.name
                    }))
                  ]}
                  placeholder="Selecione um aluno"
                  onChange={(value) => setFilters(prev => ({ ...prev, student_id: value }))}
                  defaultValue={filters.student_id}
                />
              </div>
            )}
          </div>
        </div>
      )}

      <div className="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div className="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
          <h3 className="text-lg font-semibold text-gray-800 dark:text-white/90">
            Histórico de Vendas
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
                  ID Transação
                </TableCell>
                <TableCell
                  isHeader
                  className="px-6 py-4 font-medium text-gray-500 text-start text-sm dark:text-gray-400"
                >
                  Curso
                </TableCell>
                {userRole === 'client' && (
                  <TableCell
                    isHeader
                    className="px-6 py-4 font-medium text-gray-500 text-start text-sm dark:text-gray-400"
                  >
                    Professor
                  </TableCell>
                )}
                <TableCell
                  isHeader
                  className="px-6 py-4 font-medium text-gray-500 text-start text-sm dark:text-gray-400"
                >
                  Aluno
                </TableCell>
                <TableCell
                  isHeader
                  className="px-6 py-4 font-medium text-gray-500 text-start text-sm dark:text-gray-400"
                >
                  Método
                </TableCell>
                <TableCell
                  isHeader
                  className="px-6 py-4 font-medium text-gray-500 text-start text-sm dark:text-gray-400"
                >
                  Status
                </TableCell>
                <TableCell
                  isHeader
                  className="px-6 py-4 font-medium text-gray-500 text-start text-sm dark:text-gray-400"
                >
                  Valor
                </TableCell>
                <TableCell
                  isHeader
                  className="px-6 py-4 font-medium text-gray-500 text-start text-sm dark:text-gray-400"
                >
                  Data
                </TableCell>
              </TableRow>
            </TableHeader>

            <TableBody className="divide-y divide-gray-100 dark:divide-gray-800">
              {checkouts.map((checkout) => (
                <TableRow key={checkout.id}>
                  <TableCell className="px-6 py-4">
                    <span className="font-mono text-sm text-gray-600 dark:text-gray-400">
                      {checkout.transaction_id}
                    </span>
                  </TableCell>
                  
                  <TableCell className="px-6 py-4">
                    <span className="font-medium text-gray-800 dark:text-white/90">
                      {checkout.model?.title || 'Curso'}
                    </span>
                  </TableCell>
                  
                  {userRole === 'client' && (
                    <TableCell className="px-6 py-4 text-gray-500 dark:text-gray-400">
                      {checkout.teacher?.name || 'N/A'}
                    </TableCell>
                  )}
                  
                  <TableCell className="px-6 py-4 text-gray-500 dark:text-gray-400">
                    {checkout.student?.name || 'N/A'}
                  </TableCell>
                  
                  <TableCell className="px-6 py-4 text-gray-500 dark:text-gray-400">
                    {getMethodText(checkout.method)}
                  </TableCell>
                  
                  <TableCell className="px-6 py-4">
                    <Badge color={getStatusColor(checkout.status)}>
                      {getStatusText(checkout.status)}
                    </Badge>
                  </TableCell>
                  
                  <TableCell className="px-6 py-4 text-gray-500 dark:text-gray-400">
                    {getPaymentAmount(checkout)}
                  </TableCell>
                  
                  <TableCell className="px-6 py-4 text-gray-500 dark:text-gray-400">
                    {new Date(checkout.created_at).toLocaleDateString('pt-BR')}
                  </TableCell>
                </TableRow>
              ))}
            </TableBody>
          </Table>
        </div>
      </div>

      {checkouts.length === 0 && (
        <div className="text-center py-12">
          <p className="text-gray-500 dark:text-gray-400">
            Nenhuma venda encontrada.
          </p>
        </div>
      )}
    </>
  );
}
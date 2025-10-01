import { useState, useEffect } from 'react';
import PageBreadcrumb from '../../components/common/PageBreadCrumb';
import PageMeta from '../../components/common/PageMeta';
import { Audit } from '../../types';
import { apiService } from '../../services/api';
import {
  Table,
  TableBody,
  TableCell,
  TableHeader,
  TableRow,
} from '../../components/ui/table';
import Badge from '../../components/ui/badge/Badge';
import Input from '../../components/form/input/InputField';
import Label from '../../components/form/Label';

export default function AuditList() {
  const [audits, setAudits] = useState<Audit[]>([]);
  const [loading, setLoading] = useState(true);
  const [filters, setFilters] = useState({
    user_id: '',
    auditable_type: '',
    auditable_id: '',
    date_from: '',
    date_to: ''
  });

  useEffect(() => {
    loadAudits();
  }, []);

  useEffect(() => {
    const timeoutId = setTimeout(() => {
      loadAudits();
    }, 500);

    return () => clearTimeout(timeoutId);
  }, [filters]);

  const loadAudits = async () => {
    try {
      const queryParams = Object.fromEntries(
        Object.entries(filters).filter(([_, value]) => value !== '')
      );
      
      const response = await apiService.getAudits(queryParams);
      setAudits(response.data || []);
    } catch (error) {
      console.error('Error loading audits:', error);
    } finally {
      setLoading(false);
    }
  };

  const getEventColor = (event: string): 'success' | 'warning' | 'error' | 'info' => {
    switch (event.toLowerCase()) {
      case 'created':
        return 'success';
      case 'updated':
        return 'warning';
      case 'deleted':
        return 'error';
      default:
        return 'info';
    }
  };

  const getEventText = (event: string) => {
    switch (event.toLowerCase()) {
      case 'created':
        return 'Criado';
      case 'updated':
        return 'Atualizado';
      case 'deleted':
        return 'Excluído';
      case 'restored':
        return 'Restaurado';
      default:
        return event;
    }
  };

  const getModelName = (auditableType: string) => {
    const modelMap: Record<string, string> = {
      'App\\Models\\Course': 'Curso',
      'App\\Models\\Lesson': 'Aula',
      'App\\Models\\Person': 'Pessoa',
      'App\\Models\\User': 'Usuário',
      'App\\Models\\Checkout': 'Venda',
      'App\\Models\\Enrollment': 'Matrícula'
    };
    
    return modelMap[auditableType] || auditableType;
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
        title="Auditoria | Sistema de Vendas"
        description="Logs de auditoria do sistema"
      />
      
      <PageBreadcrumb pageTitle="Auditoria" />

      {/* Filters */}
      <div className="mb-6 bg-white rounded-2xl border border-gray-200 p-6 dark:border-gray-800 dark:bg-white/[0.03]">
        <h3 className="text-lg font-semibold text-gray-800 dark:text-white/90 mb-4">
          Filtros
        </h3>
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
          <div>
            <Label>ID do Usuário</Label>
            <Input
              type="text"
              value={filters.user_id}
              onChange={(e) => setFilters(prev => ({ ...prev, user_id: e.target.value }))}
              placeholder="ID do usuário"
            />
          </div>
          
          <div>
            <Label>Tipo de Modelo</Label>
            <Input
              type="text"
              value={filters.auditable_type}
              onChange={(e) => setFilters(prev => ({ ...prev, auditable_type: e.target.value }))}
              placeholder="Ex: Course, Lesson"
            />
          </div>
          
          <div>
            <Label>ID do Modelo</Label>
            <Input
              type="text"
              value={filters.auditable_id}
              onChange={(e) => setFilters(prev => ({ ...prev, auditable_id: e.target.value }))}
              placeholder="ID do modelo"
            />
          </div>
          
          <div>
            <Label>Data Inicial</Label>
            <Input
              type="date"
              value={filters.date_from}
              onChange={(e) => setFilters(prev => ({ ...prev, date_from: e.target.value }))}
            />
          </div>
          
          <div>
            <Label>Data Final</Label>
            <Input
              type="date"
              value={filters.date_to}
              onChange={(e) => setFilters(prev => ({ ...prev, date_to: e.target.value }))}
            />
          </div>
        </div>
      </div>

      <div className="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div className="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
          <h3 className="text-lg font-semibold text-gray-800 dark:text-white/90">
            Logs de Auditoria
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
                  Evento
                </TableCell>
                <TableCell
                  isHeader
                  className="px-6 py-4 font-medium text-gray-500 text-start text-sm dark:text-gray-400"
                >
                  Modelo
                </TableCell>
                <TableCell
                  isHeader
                  className="px-6 py-4 font-medium text-gray-500 text-start text-sm dark:text-gray-400"
                >
                  ID do Modelo
                </TableCell>
                <TableCell
                  isHeader
                  className="px-6 py-4 font-medium text-gray-500 text-start text-sm dark:text-gray-400"
                >
                  Usuário
                </TableCell>
                <TableCell
                  isHeader
                  className="px-6 py-4 font-medium text-gray-500 text-start text-sm dark:text-gray-400"
                >
                  IP
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
              {audits.map((audit) => (
                <TableRow key={audit.id}>
                  <TableCell className="px-6 py-4">
                    <Badge color={getEventColor(audit.event)}>
                      {getEventText(audit.event)}
                    </Badge>
                  </TableCell>
                  
                  <TableCell className="px-6 py-4 text-gray-500 dark:text-gray-400">
                    {getModelName(audit.auditable_type)}
                  </TableCell>
                  
                  <TableCell className="px-6 py-4 text-gray-500 dark:text-gray-400">
                    {audit.auditable_id}
                  </TableCell>
                  
                  <TableCell className="px-6 py-4 text-gray-500 dark:text-gray-400">
                    {audit.user_id || 'Sistema'}
                  </TableCell>
                  
                  <TableCell className="px-6 py-4 text-gray-500 dark:text-gray-400">
                    {audit.ip_address || 'N/A'}
                  </TableCell>
                  
                  <TableCell className="px-6 py-4 text-gray-500 dark:text-gray-400">
                    {new Date(audit.created_at).toLocaleString('pt-BR')}
                  </TableCell>
                </TableRow>
              ))}
            </TableBody>
          </Table>
        </div>
      </div>

      {audits.length === 0 && (
        <div className="text-center py-12">
          <p className="text-gray-500 dark:text-gray-400">
            Nenhum log de auditoria encontrado.
          </p>
        </div>
      )}
    </>
  );
}
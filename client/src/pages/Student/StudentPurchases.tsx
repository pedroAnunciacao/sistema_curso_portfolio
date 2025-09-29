import { useState, useEffect } from 'react';
import PageMeta from '../../components/common/PageMeta';
import { Checkout } from '../../types';
import Badge from '../../components/ui/badge/Badge';
import {
  Table,
  TableBody,
  TableCell,
  TableHeader,
  TableRow,
} from '../../components/ui/table';

export default function StudentPurchases() {
  const [purchases, setPurchases] = useState<Checkout[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    // Simular carregamento de compras
    // Em uma implementação real, você faria uma chamada para a API
    setTimeout(() => {
      setPurchases([]);
      setLoading(false);
    }, 1000);
  }, []);

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
      case 'paid':
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
        title="Minhas Compras | Portal do Aluno"
        description="Histórico de compras e pagamentos"
      />
      
      <div className="mb-8">
        <h1 className="text-3xl font-bold text-gray-800 dark:text-white/90 mb-2">
          Minhas Compras
        </h1>
        <p className="text-gray-600 dark:text-gray-400">
          Acompanhe o histórico de suas compras e pagamentos
        </p>
      </div>

      {purchases.length > 0 ? (
        <div className="bg-white rounded-2xl border border-gray-200 overflow-hidden dark:border-gray-800 dark:bg-white/[0.03]">
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
                    Data
                  </TableCell>
                  <TableCell
                    isHeader
                    className="px-6 py-4 font-medium text-gray-500 text-start text-sm dark:text-gray-400"
                  >
                    Valor
                  </TableCell>
                </TableRow>
              </TableHeader>

              <TableBody className="divide-y divide-gray-100 dark:divide-gray-800">
                {purchases.map((purchase) => (
                  <TableRow key={purchase.id}>
                    <TableCell className="px-6 py-4">
                      <span className="font-medium text-gray-800 dark:text-white/90">
                        {purchase.model?.title || 'Curso'}
                      </span>
                    </TableCell>
                    
                    <TableCell className="px-6 py-4 text-gray-500 dark:text-gray-400">
                      {getMethodText(purchase.method)}
                    </TableCell>
                    
                    <TableCell className="px-6 py-4">
                      <Badge color={getStatusColor(purchase.status)}>
                        {getStatusText(purchase.status)}
                      </Badge>
                    </TableCell>
                    
                    <TableCell className="px-6 py-4 text-gray-500 dark:text-gray-400">
                      {new Date(purchase.created_at).toLocaleDateString('pt-BR')}
                    </TableCell>
                    
                    <TableCell className="px-6 py-4 text-gray-500 dark:text-gray-400">
                      R$ 199,90
                    </TableCell>
                  </TableRow>
                ))}
              </TableBody>
            </Table>
          </div>
        </div>
      ) : (
        <div className="text-center py-16">
          <div className="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6 dark:bg-gray-800">
            <svg className="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
          </div>
          <h3 className="text-xl font-semibold text-gray-800 dark:text-white/90 mb-2">
            Nenhuma compra realizada
          </h3>
          <p className="text-gray-500 dark:text-gray-400 mb-6">
            Você ainda não fez nenhuma compra. Explore nossos cursos!
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
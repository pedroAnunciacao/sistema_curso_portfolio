import { useState, useEffect } from 'react';
import { Link } from 'react-router';
import PageBreadcrumb from '../../components/common/PageBreadCrumb';
import PageMeta from '../../components/common/PageMeta';
import { Person } from '../../types';
import { apiService } from '../../services/api';
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

export default function PeopleList() {
  const [people, setPeople] = useState<Person[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    loadPeople();
  }, []);

  const loadPeople = async () => {
    try {
      const response = await apiService.getPeople();
      setPeople(response.data.data || []);
    } catch (error) {
      console.error('Error loading people:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleDelete = async (id: number) => {
    if (window.confirm('Tem certeza que deseja excluir esta pessoa?')) {
      try {
        await apiService.deletePerson(id);
        loadPeople();
      } catch (error) {
        console.error('Error deleting person:', error);
      }
    }
  };

  const getPersonType = (person: Person) => {
    if (person.client) return 'Cliente';
    if (person.teacher) return 'Professor';
    if (person.student) return 'Aluno';
    return 'N/A';
  };

  const getPersonTypeColor = (person: Person): 'primary' | 'success' | 'warning' => {
    if (person.client) return 'primary';
    if (person.teacher) return 'success';
    if (person.student) return 'warning';
    return 'primary';
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
        title="Pessoas | Sistema de Vendas"
        description="Gerenciar pessoas do sistema"
      />
      
      <div className="flex items-center justify-between mb-6">
        <PageBreadcrumb pageTitle="Pessoas" />
        <Link to="/people/create">
          <Button startIcon={<PlusIcon className="size-4" />}>
            Nova Pessoa
          </Button>
        </Link>
      </div>

      <div className="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div className="max-w-full overflow-x-auto">
          <Table>
            <TableHeader className="border-b border-gray-100 dark:border-gray-800">
              <TableRow>
                <TableCell
                  isHeader
                  className="px-6 py-4 font-medium text-gray-500 text-start text-sm dark:text-gray-400"
                >
                  Nome
                </TableCell>
                <TableCell
                  isHeader
                  className="px-6 py-4 font-medium text-gray-500 text-start text-sm dark:text-gray-400"
                >
                  Tipo
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
              {people.map((person) => (
                <TableRow key={person.id}>
                  <TableCell className="px-6 py-4">
                    <div className="flex items-center gap-3">
                      <div className="w-10 h-10 bg-brand-100 rounded-full flex items-center justify-center dark:bg-brand-500/20">
                        <span className="text-sm font-medium text-brand-600 dark:text-brand-400">
                          {person.name.charAt(0).toUpperCase()}
                        </span>
                      </div>
                      <span className="font-medium text-gray-800 dark:text-white/90">
                        {person.name}
                      </span>
                    </div>
                  </TableCell>
                  
                  <TableCell className="px-6 py-4">
                    <Badge color={getPersonTypeColor(person)}>
                      {getPersonType(person)}
                    </Badge>
                  </TableCell>
                  
                  <TableCell className="px-6 py-4 text-gray-500 dark:text-gray-400">
                    {new Date(person.created_at).toLocaleDateString('pt-BR')}
                  </TableCell>
                  
                  <TableCell className="px-6 py-4">
                    <div className="flex gap-2">
                      <Link to={`/people/${person.id}/edit`}>
                        <Button size="sm" variant="outline">
                          Editar
                        </Button>
                      </Link>
                      <Button
                        size="sm"
                        variant="outline"
                        onClick={() => handleDelete(person.id)}
                        className="text-error-500 hover:text-error-600"
                      >
                        Excluir
                      </Button>
                    </div>
                  </TableCell>
                </TableRow>
              ))}
            </TableBody>
          </Table>
        </div>
      </div>

      {people.length === 0 && (
        <div className="text-center py-12">
          <p className="text-gray-500 dark:text-gray-400">
            Nenhuma pessoa encontrada.
          </p>
        </div>
      )}
    </>
  );
}
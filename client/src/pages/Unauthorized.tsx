import { Link } from 'react-router';
import PageMeta from '../components/common/PageMeta';
import Button from '../components/ui/button/Button';

export default function Unauthorized() {
  return (
    <>
      <PageMeta
        title="Acesso Negado | Sistema de Vendas"
        description="Você não tem permissão para acessar esta página"
      />
      
      <div className="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900">
        <div className="text-center">
          <div className="w-24 h-24 bg-error-100 rounded-full flex items-center justify-center mx-auto mb-6 dark:bg-error-500/20">
            <svg className="w-12 h-12 text-error-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z" />
            </svg>
          </div>
          
          <h1 className="text-3xl font-bold text-gray-800 dark:text-white/90 mb-4">
            Acesso Negado
          </h1>
          
          <p className="text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">
            Você não tem permissão para acessar esta página. Entre em contato com o administrador se acredita que isso é um erro.
          </p>
          
          <div className="flex gap-4 justify-center">
            <Button variant="outline" onClick={() => window.history.back()}>
              Voltar
            </Button>
            <Link to="/">
              <Button>
                Ir para Dashboard
              </Button>
            </Link>
          </div>
        </div>
      </div>
    </>
  );
}
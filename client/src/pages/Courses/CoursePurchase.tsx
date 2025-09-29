import { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router';
import PageBreadcrumb from '../../components/common/PageBreadCrumb';
import PageMeta from '../../components/common/PageMeta';
import { Course } from '../../types';
import { apiService } from '../../services/api';
import { useAuth } from '../../context/AuthContext';
import Button from '../../components/ui/button/Button';
import Input from '../../components/form/input/InputField';
import Label from '../../components/form/Label';
import { Modal } from '../../components/ui/modal';
import { useModal } from '../../hooks/useModal';

export default function CoursePurchase() {
  const { id } = useParams();
  const navigate = useNavigate();
  const { user } = useAuth();
  const [course, setCourse] = useState<Course | null>(null);
  const [loading, setLoading] = useState(true);
  const [paymentMethod, setPaymentMethod] = useState<'pix' | 'card' | 'boleto'>('pix');
  const [paymentData, setPaymentData] = useState({
    amount: 199.90,
    description: '',
    payer: {
      email: '',
      first_name: '',
      last_name: '',
      identification: {
        type: 'CPF',
        number: ''
      }
    }
  });
  const [cardData, setCardData] = useState({
    id: '',
    card_number: '',
    installments: 1
  });

  const { isOpen, openModal, closeModal } = useModal();
  const [qrCode, setQrCode] = useState<string>('');

  useEffect(() => {
    if (id) {
      loadCourse();
    }
  }, [id]);

  useEffect(() => {
    if (user?.person) {
      setPaymentData(prev => ({
        ...prev,
        payer: {
          ...prev.payer,
          email: user.person.name + '@email.com', // Ajustar conforme necessário
          first_name: user.person.name.split(' ')[0] || '',
          last_name: user.person.name.split(' ').slice(1).join(' ') || ''
        }
      }));
    }
  }, [user]);

  const loadCourse = async () => {
    try {
      const response = await apiService.getCourse(Number(id));
      const courseData = response.data;
      setCourse(courseData);
      setPaymentData(prev => ({
        ...prev,
        description: `Curso: ${courseData.title}`
      }));
    } catch (error) {
      console.error('Error loading course:', error);
    } finally {
      setLoading(false);
    }
  };

  const handlePixPayment = async () => {
    try {
      const pixPaymentData = {
        ...paymentData,
        model_type: 'courses',
        model_id: Number(id),
        expiration_minutes: 30
      };

      const response = await apiService.processPixPayment(pixPaymentData);
      
      if (response.data?.mercado_pago?.qr_code) {
        setQrCode(response.data.mercado_pago.qr_code);
        openModal();
      }
    } catch (error) {
      console.error('Error processing PIX payment:', error);
      alert('Erro ao processar pagamento PIX');
    }
  };

  const handleCardPayment = async () => {
    try {
      const cardPaymentData = {
        ...paymentData,
        card: cardData,
        model_type: 'courses',
        model_id: Number(id)
      };

      const response = await apiService.processCardPayment(cardPaymentData);
      alert('Pagamento processado com sucesso!');
      navigate('/my-courses');
    } catch (error) {
      console.error('Error processing card payment:', error);
      alert('Erro ao processar pagamento com cartão');
    }
  };

  const handleBoletoPayment = async () => {
    try {
      const boletoPaymentData = {
        ...paymentData,
        model_type: 'courses',
        model_id: Number(id),
        payer: {
          ...paymentData.payer,
          address: {
            zip_code: '01310-100',
            street_name: 'Av. Paulista',
            street_number: '1000',
            neighborhood: 'Bela Vista',
            city: 'São Paulo',
            federal_unit: 'SP'
          }
        }
      };

      const response = await apiService.processBoletoPayment(boletoPaymentData);
      
      if (response.data?.mercado_pago?.ticket_url) {
        window.open(response.data.mercado_pago.ticket_url, '_blank');
      }
    } catch (error) {
      console.error('Error processing boleto payment:', error);
      alert('Erro ao processar pagamento por boleto');
    }
  };

  const handlePayment = () => {
    switch (paymentMethod) {
      case 'pix':
        handlePixPayment();
        break;
      case 'card':
        handleCardPayment();
        break;
      case 'boleto':
        handleBoletoPayment();
        break;
    }
  };

  if (loading) {
    return (
      <div className="flex items-center justify-center min-h-screen">
        <div className="animate-spin rounded-full h-32 w-32 border-b-2 border-brand-500"></div>
      </div>
    );
  }

  if (!course) {
    return (
      <div className="text-center py-12">
        <p className="text-gray-500 dark:text-gray-400">Curso não encontrado.</p>
      </div>
    );
  }

  return (
    <>
      <PageMeta
        title={`Comprar ${course.title} | Sistema de Vendas`}
        description={`Comprar curso ${course.title}`}
      />
      
      <PageBreadcrumb pageTitle={`Comprar: ${course.title}`} />

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {/* Course Info */}
        <div className="lg:col-span-2">
          <div className="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h1 className="text-2xl font-bold text-gray-800 dark:text-white/90 mb-4">
              {course.title}
            </h1>
            
            {course.image && (
              <img
                src={course.image}
                alt={course.title}
                className="w-full h-64 object-cover rounded-xl mb-6"
              />
            )}
            
            <p className="text-gray-600 dark:text-gray-400 mb-6">
              {course.description}
            </p>

            <div className="border-t border-gray-200 dark:border-gray-800 pt-6">
              <h3 className="text-lg font-semibold text-gray-800 dark:text-white/90 mb-4">
                Conteúdo do Curso
              </h3>
              
              {course.lessons && course.lessons.length > 0 ? (
                <div className="space-y-3">
                  {course.lessons.map((lesson, index) => (
                    <div
                      key={lesson.id}
                      className="flex items-center gap-3 p-3 border border-gray-200 rounded-lg dark:border-gray-800"
                    >
                      <div className="flex items-center justify-center w-6 h-6 bg-brand-100 rounded-full dark:bg-brand-500/20">
                        <span className="text-xs font-medium text-brand-600 dark:text-brand-400">
                          {index + 1}
                        </span>
                      </div>
                      <span className="text-gray-800 dark:text-white/90">
                        {lesson.title}
                      </span>
                    </div>
                  ))}
                </div>
              ) : (
                <p className="text-gray-500 dark:text-gray-400">
                  Nenhuma aula disponível ainda.
                </p>
              )}
            </div>
          </div>
        </div>

        {/* Payment Form */}
        <div className="lg:col-span-1">
          <div className="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] sticky top-6">
            <h2 className="text-xl font-semibold text-gray-800 dark:text-white/90 mb-6">
              Finalizar Compra
            </h2>

            <div className="mb-6">
              <div className="flex items-center justify-between p-4 bg-gray-50 rounded-lg dark:bg-gray-800/50">
                <span className="font-medium text-gray-800 dark:text-white/90">
                  Total:
                </span>
                <span className="text-xl font-bold text-brand-600 dark:text-brand-400">
                  R$ {paymentData.amount.toFixed(2)}
                </span>
              </div>
            </div>

            {/* Payment Method Selection */}
            <div className="mb-6">
              <Label>Método de Pagamento</Label>
              <div className="space-y-3 mt-2">
                <label className="flex items-center gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-800/50">
                  <input
                    type="radio"
                    name="payment"
                    value="pix"
                    checked={paymentMethod === 'pix'}
                    onChange={(e) => setPaymentMethod(e.target.value as 'pix')}
                    className="text-brand-500"
                  />
                  <span className="text-gray-800 dark:text-white/90">PIX</span>
                </label>
                
                <label className="flex items-center gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-800/50">
                  <input
                    type="radio"
                    name="payment"
                    value="card"
                    checked={paymentMethod === 'card'}
                    onChange={(e) => setPaymentMethod(e.target.value as 'card')}
                    className="text-brand-500"
                  />
                  <span className="text-gray-800 dark:text-white/90">Cartão de Crédito</span>
                </label>
                
                <label className="flex items-center gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-800/50">
                  <input
                    type="radio"
                    name="payment"
                    value="boleto"
                    checked={paymentMethod === 'boleto'}
                    onChange={(e) => setPaymentMethod(e.target.value as 'boleto')}
                    className="text-brand-500"
                  />
                  <span className="text-gray-800 dark:text-white/90">Boleto</span>
                </label>
              </div>
            </div>

            {/* Card Form */}
            {paymentMethod === 'card' && (
              <div className="space-y-4 mb-6">
                <div>
                  <Label>Número do Cartão</Label>
                  <Input
                    type="text"
                    value={cardData.card_number}
                    onChange={(e) => setCardData(prev => ({ ...prev, card_number: e.target.value }))}
                    placeholder="1234 5678 9012 3456"
                  />
                </div>
                <div>
                  <Label>Parcelas</Label>
                  <select
                    value={cardData.installments}
                    onChange={(e) => setCardData(prev => ({ ...prev, installments: Number(e.target.value) }))}
                    className="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm shadow-theme-xs focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:focus:border-brand-800"
                  >
                    {[1, 2, 3, 6, 12].map(num => (
                      <option key={num} value={num}>
                        {num}x de R$ {(paymentData.amount / num).toFixed(2)}
                      </option>
                    ))}
                  </select>
                </div>
              </div>
            )}

            {/* Payer Info */}
            <div className="space-y-4 mb-6">
              <div>
                <Label>CPF</Label>
                <Input
                  type="text"
                  value={paymentData.payer.identification.number}
                  onChange={(e) => setPaymentData(prev => ({
                    ...prev,
                    payer: {
                      ...prev.payer,
                      identification: {
                        ...prev.payer.identification,
                        number: e.target.value
                      }
                    }
                  }))}
                  placeholder="000.000.000-00"
                />
              </div>
            </div>

            <Button
              className="w-full"
              onClick={handlePayment}
              disabled={loading}
            >
              {loading ? 'Processando...' : 'Finalizar Compra'}
            </Button>
          </div>
        </div>
      </div>

      {/* PIX QR Code Modal */}
      <Modal isOpen={isOpen} onClose={closeModal} className="max-w-md">
        <div className="p-6 text-center">
          <h3 className="text-lg font-semibold text-gray-800 dark:text-white/90 mb-4">
            Pagamento PIX
          </h3>
          
          {qrCode && (
            <div className="mb-4">
              <div className="p-4 bg-white rounded-lg border border-gray-200">
                <img
                  src={`data:image/png;base64,${qrCode}`}
                  alt="QR Code PIX"
                  className="w-full max-w-xs mx-auto"
                />
              </div>
            </div>
          )}
          
          <p className="text-sm text-gray-500 dark:text-gray-400 mb-6">
            Escaneie o QR Code com seu app do banco ou copie o código PIX
          </p>
          
          <Button onClick={closeModal} className="w-full">
            Fechar
          </Button>
        </div>
      </Modal>
    </>
  );
}
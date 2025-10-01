export interface User {
  id: number;
  email: string;
  ativado: boolean;
  person: Person;
}

export interface Person {
  id: number;
  name: string;
  client?: Client;
  teacher?: Teacher;
  student?: Student;
  Addresses?: Address;
  Contacts?: Contact[];
  created_at: string;
  updated_at: string;
}

export interface Client {
  id: number;
  created_at: string;
  updated_at: string;
}

export interface Teacher {
  id: number;
  name: string;
  created_at: string;
  updated_at: string;
}

export interface Student {
  id: number;
  email_educacional: string;
  name: string;
  created_at: string;
  updated_at: string;
}

export interface Address {
  id: number;
  zip_code: string;
  street: string;
  number: string;
  neighborhood: string;
  complement?: string;
}

export interface Contact {
  id: number;
  type: string;
  conteudo: string;
}

export interface Course {
  id: number;
  title: string;
  description: string;
  price?: string;
  image?: string;
  teacher?: Teacher;
  lessons?: Lesson[];
  students?: Person[];
  created_at: string;
  updated_at: string;
}

export interface Lesson {
  id: number;
  title: string;
  content: string;
  image?: string;
  link_youtube?: string;
  course?: Course;
  created_at: string;
  updated_at: string;
}

export interface Enrollment {
  id: number;
  person?: Person;
  course?: Course;
  created_at: string;
  updated_at: string;
}

export interface Checkout {
  id: number;
  transaction_id: string;
  method: string;
  status: string;
  model_type: string;
  model_id: number;
  teacher?: Teacher;
  student?: Student;
  model?: any;
  mercado_pago?: {
    id: number;
    status: string;
    transaction_amount: number;
    qr_code?: string;
    qr_code_base64?: string;
    barcode?: string;
    ticket_url?: string;
    installments?: number;
    status_detail?: string;
    idempotency_key: string;
  };
  created_at: string;
  updated_at: string;
}

export interface Audit {
  id: number;
  event: string;
  auditable_type: string;
  auditable_id: number;
  old_values?: any;
  new_values?: any;
  user_id?: number;
  url?: string;
  ip_address?: string;
  user_agent?: string;
  tags?: string;
  created_at: string;
}

export interface ApiResponse<T> {
  status: string;
  message: string;
  data: T;
  code: number;
}

export interface PaginatedResponse<T> {
  data: T[];
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
}
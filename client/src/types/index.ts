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
  created_at: string;
  updated_at: string;
}

export interface Student {
  id: number;
  email_educacional: string;
  person?: Person;
  created_at: string;
  updated_at: string;
}

export interface Course {
  id: number;
  title: string;
  description: string;
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
  mercado_pago?: any;
  created_at: string;
  updated_at: string;
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
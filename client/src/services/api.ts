const API_BASE_URL = 'http://localhost:8000/api';

class ApiService {
  private token: string | null = null;
  private userId: string | null = null;

  constructor() {
    this.token = localStorage.getItem('token');
    this.userId = localStorage.getItem('userId');
  }

  private getHeaders() {
    const headers: Record<string, string> = {
      'Content-Type': 'application/json',
    };

    if (this.token) {
      headers['Authorization'] = `Bearer ${this.token}`;
    }

    if (this.userId) {
      headers['X-Content'] = this.userId;
    }

    return headers;
  }

  private async request<T>(endpoint: string, options: RequestInit = {}): Promise<T> {
    const url = `${API_BASE_URL}${endpoint}`;
    
    const response = await fetch(url, {
      ...options,
      headers: {
        ...this.getHeaders(),
        ...options.headers,
      },
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    return response.json();
  }

  // Auth
  async login(username: string, password: string) {
    const response = await this.request<any>('/auth/login', {
      method: 'POST',
      body: JSON.stringify({ username, password }),
    });

    if (response.accessToken) {
      this.token = response.accessToken;
      localStorage.setItem('token', response.accessToken);
      
      // Fazer segunda requisição para obter dados do usuário
      const userResponse = await this.me();
      return userResponse;
    }

    return response;
  }

  async me() {
    const response = await this.request<any>('/auth/me');
    if (response.data?.id) {
      this.userId = response.data.id.toString();
      localStorage.setItem('userId', response.data.id.toString());
    }
    return response;
  }

  logout() {
    this.token = null;
    this.userId = null;
    localStorage.removeItem('token');
    localStorage.removeItem('userId');
  }

  // Teachers
  async getTeachers(queryParams?: any) {
    const params = queryParams ? `?queryParams=${encodeURIComponent(JSON.stringify(queryParams))}` : '';
    return this.request<any>(`/teachers${params}`);
  }

  // Students
  async getStudents(queryParams?: any) {
    const params = queryParams ? `?queryParams=${encodeURIComponent(JSON.stringify(queryParams))}` : '';
    return this.request<any>(`/students${params}`);
  }

  // Courses
  async getCourses(queryParams?: any) {
    const params = queryParams ? `?queryParams=${encodeURIComponent(JSON.stringify(queryParams))}` : '';
    return this.request<any>(`/courses${params}`);
  }

  async getCourse(id: number) {
    return this.request<any>(`/courses/${id}`);
  }

  async createCourse(course: any) {
    return this.request<any>('/courses', {
      method: 'POST',
      body: JSON.stringify({ course }),
    });
  }

  async updateCourse(course: any) {
    return this.request<any>('/courses', {
      method: 'PUT',
      body: JSON.stringify({ course }),
    });
  }

  async deleteCourse(id: number) {
    return this.request<any>(`/courses/${id}`, {
      method: 'DELETE',
    });
  }

  async getCoursesByTeacher(teacherId: number, queryParams?: any) {
    const params = queryParams ? `?queryParams=${encodeURIComponent(JSON.stringify(queryParams))}` : '';
    return this.request<any>(`/courses/by-teacher/${teacherId}${params}`);
  }

  // Lessons
  async getLessons(queryParams?: any) {
    const params = queryParams ? `?queryParams=${encodeURIComponent(JSON.stringify(queryParams))}` : '';
    return this.request<any>(`/lessons${params}`);
  }

  async getLesson(id: number) {
    return this.request<any>(`/lessons/${id}`);
  }

  async createLesson(lesson: any) {
    return this.request<any>('/lessons', {
      method: 'POST',
      body: JSON.stringify({ lesson }),
    });
  }

  async updateLesson(lesson: any) {
    return this.request<any>('/lessons', {
      method: 'PUT',
      body: JSON.stringify({ lesson }),
    });
  }

  async deleteLesson(id: number) {
    return this.request<any>(`/lessons/${id}`, {
      method: 'DELETE',
    });
  }

  // People
  async getPeople(queryParams?: any) {
    const params = queryParams ? `?queryParams=${encodeURIComponent(JSON.stringify(queryParams))}` : '';
    return this.request<any>(`/people${params}`);
  }

  async getPerson(id: number) {
    return this.request<any>(`/people/${id}`);
  }

  async createPerson(person: any) {
    return this.request<any>('/people', {
      method: 'POST',
      body: JSON.stringify({ person }),
    });
  }

  async updatePerson(person: any) {
    return this.request<any>('/people', {
      method: 'PUT',
      body: JSON.stringify({ person }),
    });
  }

  async deletePerson(id: number) {
    return this.request<any>(`/people/${id}`, {
      method: 'DELETE',
    });
  }

  // Enrollments
  async getEnrollments(queryParams?: any) {
    const params = queryParams ? `?queryParams=${encodeURIComponent(JSON.stringify(queryParams))}` : '';
    return this.request<any>(`/enrollments${params}`);
  }

  async createEnrollment(enrollment: any) {
    return this.request<any>('/enrollments', {
      method: 'POST',
      body: JSON.stringify({ enrollment }),
    });
  }

  // Checkouts
  async getCheckouts(queryParams?: any) {
    const params = queryParams ? `?queryParams=${encodeURIComponent(JSON.stringify(queryParams))}` : '';
    return this.request<any>(`/payments${params}`);
  }

  // Card Token
  async createCardToken(cardData: any) {
    const publicKey = 'TEST-c5ec2c52-51c2-40d4-95db-68bcc5e9bf82';
    const response = await fetch(`https://api.mercadopago.com/v1/card_tokens?public_key=${publicKey}`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(cardData),
    });

    if (!response.ok) {
      throw new Error('Erro ao criar token do cartão');
    }

    return response.json();
  }

  // Payments
  async processPixPayment(paymentData: any) {
    return this.request<any>('/payments/pix', {
      method: 'POST',
      body: JSON.stringify({ paymentPix: paymentData }),
    });
  }

  async processCardPayment(paymentData: any) {
    return this.request<any>('/payments/credit-card', {
      method: 'POST',
      body: JSON.stringify({ paymentCard: paymentData }),
    });
  }

  async processBoletoPayment(paymentData: any) {
    return this.request<any>('/payments/boleto', {
      method: 'POST',
      body: JSON.stringify({ paymentBoleto: paymentData }),
    });
  }

  // Audit
  async getAudits(filters?: any) {
    const params = new URLSearchParams(filters).toString();
    return this.request<any>(`/audit${params ? `?${params}` : ''}`);
  }
}

export const apiService = new ApiService();
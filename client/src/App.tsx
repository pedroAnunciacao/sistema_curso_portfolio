import { BrowserRouter as Router, Routes, Route } from "react-router";
import { AuthProvider } from "./context/AuthContext";
import { ProtectedRoute } from "./components/auth/ProtectedRoute";
import SignIn from "./pages/AuthPages/SignIn";
import SignUp from "./pages/AuthPages/SignUp";
import NotFound from "./pages/OtherPage/NotFound";
import UserProfiles from "./pages/UserProfiles";
import Videos from "./pages/UiElements/Videos";
import Images from "./pages/UiElements/Images";
import Alerts from "./pages/UiElements/Alerts";
import Badges from "./pages/UiElements/Badges";
import Avatars from "./pages/UiElements/Avatars";
import Buttons from "./pages/UiElements/Buttons";
import LineChart from "./pages/Charts/LineChart";
import BarChart from "./pages/Charts/BarChart";
import Calendar from "./pages/Calendar";
import BasicTables from "./pages/Tables/BasicTables";
import FormElements from "./pages/Forms/FormElements";
import Blank from "./pages/Blank";
import AppLayout from "./layout/AppLayout";
import { ScrollToTop } from "./components/common/ScrollToTop";
import Home from "./pages/Dashboard/Home";
import CourseList from "./pages/Courses/CourseList";
import CourseForm from "./pages/Courses/CourseForm";
import CourseDetails from "./pages/Courses/CourseDetails";
import CoursePurchase from "./pages/Courses/CoursePurchase";
import LessonForm from "./pages/Lessons/LessonForm";
import PeopleList from "./pages/People/PeopleList";
import CheckoutList from "./pages/Checkouts/CheckoutList";
import AuditList from "./pages/Audit/AuditList";
import StudentLayout from "./pages/Student/StudentLayout";
import StudentDashboard from "./pages/Student/StudentDashboard";
import StudentCourses from "./pages/Student/StudentCourses";
import MyCourses from "./pages/Student/MyCourses";
import StudentPurchases from "./pages/Student/StudentPurchases";
import Unauthorized from "./pages/Unauthorized";

export default function App() {
  return (
    <AuthProvider>
      <Router>
        <ScrollToTop />
        <Routes>
          {/* Student Layout */}
          <Route element={
            <ProtectedRoute allowedRoles={['student']}>
              <StudentLayout />
            </ProtectedRoute>
          }>
            <Route path="/student" element={<StudentDashboard />} />
            <Route path="/student/courses" element={<StudentCourses />} />
            <Route path="/student/my-courses" element={<MyCourses />} />
            <Route path="/student/purchases" element={<StudentPurchases />} />
          </Route>

          {/* Admin/Teacher Dashboard Layout */}
          <Route element={<AppLayout />}>
            <Route index path="/" element={
              <ProtectedRoute allowedRoles={['client', 'teacher']}>
                <Home />
              </ProtectedRoute>
            } />

            {/* Courses */}
            <Route path="/courses" element={
              <ProtectedRoute>
                <CourseList />
              </ProtectedRoute>
            } />
            <Route path="/courses/create" element={
              <ProtectedRoute allowedRoles={['client', 'teacher']}>
                <CourseForm />
              </ProtectedRoute>
            } />
            <Route path="/courses/:id" element={
              <ProtectedRoute>
                <CourseDetails />
              </ProtectedRoute>
            } />
            <Route path="/courses/:id/edit" element={
              <ProtectedRoute allowedRoles={['client', 'teacher']}>
                <CourseForm />
              </ProtectedRoute>
            } />
            <Route path="/courses/:id/purchase" element={
              <ProtectedRoute allowedRoles={['student']}>
                <CoursePurchase />
              </ProtectedRoute>
            } />

            {/* Lessons */}
            <Route path="/courses/:courseId/lessons/create" element={
              <ProtectedRoute allowedRoles={['client', 'teacher']}>
                <LessonForm />
              </ProtectedRoute>
            } />
            <Route path="/lessons/:id/edit" element={
              <ProtectedRoute allowedRoles={['client', 'teacher']}>
                <LessonForm />
              </ProtectedRoute>
            } />

            {/* People */}
            <Route path="/people" element={
              <ProtectedRoute allowedRoles={['client']}>
                <PeopleList />
              </ProtectedRoute>
            } />

            {/* Checkouts */}
            <Route path="/checkouts" element={
              <ProtectedRoute allowedRoles={['client', 'teacher']}>
                <CheckoutList />
              </ProtectedRoute>
            } />

            {/* Audit */}
            <Route path="/audit" element={
              <ProtectedRoute allowedRoles={['client']}>
                <AuditList />
              </ProtectedRoute>
            } />

            {/* Others Page */}
            <Route path="/profile" element={
              <ProtectedRoute>
                <UserProfiles />
              </ProtectedRoute>
            } />
            <Route path="/calendar" element={
              <ProtectedRoute>
                <Calendar />
              </ProtectedRoute>
            } />
            <Route path="/blank" element={
              <ProtectedRoute>
                <Blank />
              </ProtectedRoute>
            } />

            {/* Forms */}
            <Route path="/form-elements" element={
              <ProtectedRoute>
                <FormElements />
              </ProtectedRoute>
            } />

            {/* Tables */}
            <Route path="/basic-tables" element={
              <ProtectedRoute>
                <BasicTables />
              </ProtectedRoute>
            } />

            {/* Ui Elements */}
            <Route path="/alerts" element={
              <ProtectedRoute>
                <Alerts />
              </ProtectedRoute>
            } />
            <Route path="/avatars" element={
              <ProtectedRoute>
                <Avatars />
              </ProtectedRoute>
            } />
            <Route path="/badge" element={
              <ProtectedRoute>
                <Badges />
              </ProtectedRoute>
            } />
            <Route path="/buttons" element={
              <ProtectedRoute>
                <Buttons />
              </ProtectedRoute>
            } />
            <Route path="/images" element={
              <ProtectedRoute>
                <Images />
              </ProtectedRoute>
            } />
            <Route path="/videos" element={
              <ProtectedRoute>
                <Videos />
              </ProtectedRoute>
            } />

            {/* Charts */}
            <Route path="/line-chart" element={
              <ProtectedRoute>
                <LineChart />
              </ProtectedRoute>
            } />
            <Route path="/bar-chart" element={
              <ProtectedRoute>
                <BarChart />
              </ProtectedRoute>
            } />
          </Route>

          {/* Auth Layout */}
          <Route path="/signin" element={<SignIn />} />
          <Route path="/signup" element={<SignUp />} />
          <Route path="/unauthorized" element={<Unauthorized />} />

          {/* Fallback Route */}
          <Route path="*" element={<NotFound />} />
        </Routes>
      </Router>
    </AuthProvider>
  );
}

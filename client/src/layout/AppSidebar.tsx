import { useState, useEffect } from "react";
import { Link, useLocation } from "react-router";
import { useSidebar } from "../context/SidebarContext";
import { useAuth } from "../context/AuthContext";
import SidebarWidget from "./SidebarWidget";
import {
  BoxCubeIcon,
  ListIcon,
  TableIcon,
  PageIcon,
  PieChartIcon,
  PlugInIcon,
  DocsIcon,
  MailIcon,
  ChatIcon,
  TaskIcon,
  UserCircleIcon,
  CalenderIcon,
} from "../icons";

interface MenuItem {
  title: string;
  path?: string;
  icon: React.ReactNode;
  children?: MenuItem[];
  roles?: ('client' | 'teacher' | 'student')[];
}

const AppSidebar: React.FC = () => {
  const {
    isExpanded,
    isMobileOpen,
    isHovered,
    activeItem,
    openSubmenu,
    setIsHovered,
    setActiveItem,
    toggleSubmenu,
  } = useSidebar();
  const location = useLocation();
  const { getUserRole } = useAuth();
  const userRole = getUserRole();

  const menuItems: MenuItem[] = [
    {
      title: "Dashboard",
      path: "/",
      icon: <BoxCubeIcon />,
      roles: ['client', 'teacher']
    },
    {
      title: "Cursos",
      icon: <DocsIcon />,
      roles: ['client', 'teacher'],
      children: [
        { title: "Lista de Cursos", path: "/courses", icon: <ListIcon /> },
        { title: "Criar Curso", path: "/courses/create", icon: <PageIcon /> },
      ]
    },
    {
      title: "Pessoas",
      path: "/people",
      icon: <UserCircleIcon />,
      roles: ['client']
    },
    {
      title: "Vendas",
      path: "/checkouts",
      icon: <TableIcon />,
      roles: ['client', 'teacher']
    },
    {
      title: "Auditoria",
      path: "/audit",
      icon: <TaskIcon />,
      roles: ['client']
    },
    {
      title: "Calendário",
      path: "/calendar",
      icon: <CalenderIcon />,
      roles: ['client', 'teacher']
    },
    {
      title: "Perfil",
      path: "/profile",
      icon: <UserCircleIcon />,
      roles: ['client', 'teacher']
    },
    {
      title: "UI Elements",
      icon: <PlugInIcon />,
      roles: ['client'],
      children: [
        { title: "Alerts", path: "/alerts", icon: <PageIcon /> },
        { title: "Buttons", path: "/buttons", icon: <PageIcon /> },
        { title: "Badges", path: "/badge", icon: <PageIcon /> },
        { title: "Avatars", path: "/avatars", icon: <PageIcon /> },
        { title: "Images", path: "/images", icon: <PageIcon /> },
        { title: "Videos", path: "/videos", icon: <PageIcon /> },
      ]
    },
    {
      title: "Forms",
      icon: <MailIcon />,
      roles: ['client'],
      children: [
        { title: "Form Elements", path: "/form-elements", icon: <PageIcon /> },
      ]
    },
    {
      title: "Tables",
      icon: <TableIcon />,
      roles: ['client'],
      children: [
        { title: "Basic Tables", path: "/basic-tables", icon: <PageIcon /> },
      ]
    },
    {
      title: "Charts",
      icon: <PieChartIcon />,
      roles: ['client'],
      children: [
        { title: "Line Chart", path: "/line-chart", icon: <PageIcon /> },
        { title: "Bar Chart", path: "/bar-chart", icon: <PageIcon /> },
      ]
    },
    {
      title: "Blank Page",
      path: "/blank",
      icon: <PageIcon />,
      roles: ['client']
    },
  ];

  // Filter menu items based on user role
  const filteredMenuItems = menuItems.filter(item => 
    !item.roles || item.roles.includes(userRole!)
  );

  const isActive = (path: string) => location.pathname === path;

  const isParentActive = (children?: MenuItem[]) => {
    return children?.some(child => child.path && isActive(child.path)) || false;
  };

  useEffect(() => {
    const currentItem = filteredMenuItems.find(item => {
      if (item.path && isActive(item.path)) return true;
      if (item.children) {
        return item.children.some(child => child.path && isActive(child.path));
      }
      return false;
    });

    if (currentItem) {
      setActiveItem(currentItem.title);
    }
  }, [location.pathname, setActiveItem, filteredMenuItems]);

  return (
    <aside
      className={`fixed left-0 top-0 z-50 flex h-screen flex-col overflow-y-hidden bg-white duration-300 ease-linear dark:bg-gray-900 lg:static lg:translate-x-0 ${
        isExpanded || isHovered ? "w-[290px]" : "w-[90px]"
      } ${isMobileOpen ? "translate-x-0" : "-translate-x-full"}`}
      onMouseEnter={() => setIsHovered(true)}
      onMouseLeave={() => setIsHovered(false)}
    >
      {/* SIDEBAR HEADER */}
      <div className="flex items-center justify-between gap-2 px-6 py-5.5 lg:py-6.5">
        <Link to="/">
          <img
            className="dark:hidden"
            src="./images/logo/logo.svg"
            alt="Logo"
          />
          <img
            className="hidden dark:block"
            src="./images/logo/logo-dark.svg"
            alt="Logo"
          />
        </Link>
      </div>
      {/* SIDEBAR HEADER */}

      <div className="no-scrollbar flex flex-col overflow-y-auto duration-300 ease-linear">
        {/* Sidebar Menu */}
        <nav className="mt-5 px-4 py-4 lg:mt-9 lg:px-6">
          <div>
            <h3 className="mb-4 ml-4 text-sm font-semibold text-gray-500 dark:text-gray-400">
              MENU
            </h3>

            <ul className="mb-6 flex flex-col gap-1.5">
              {filteredMenuItems.map((menuItem) => (
                <li key={menuItem.title}>
                  {menuItem.children ? (
                    <div>
                      <button
                        onClick={() => toggleSubmenu(menuItem.title)}
                        className={`menu-item group relative flex w-full items-center gap-3 rounded-lg px-3 py-2 font-medium text-theme-sm ${
                          isParentActive(menuItem.children)
                            ? "menu-item-active"
                            : "menu-item-inactive"
                        }`}
                      >
                        <span
                          className={`menu-item-icon-size ${
                            isParentActive(menuItem.children)
                              ? "menu-item-icon-active"
                              : "menu-item-icon-inactive"
                          }`}
                        >
                          {menuItem.icon}
                        </span>
                        {(isExpanded || isHovered) && (
                          <>
                            {menuItem.title}
                            <svg
                              className={`menu-item-arrow absolute right-3 top-1/2 -translate-y-1/2 fill-current transition-transform duration-200 ${
                                openSubmenu === menuItem.title
                                  ? "menu-item-arrow-active"
                                  : "menu-item-arrow-inactive"
                              }`}
                              width="20"
                              height="20"
                              viewBox="0 0 20 20"
                              fill="none"
                              xmlns="http://www.w3.org/2000/svg"
                            >
                              <path
                                fillRule="evenodd"
                                clipRule="evenodd"
                                d="M4.41107 6.9107C4.73651 6.58527 5.26414 6.58527 5.58958 6.9107L10.0003 11.3214L14.4111 6.91071C14.7365 6.58527 15.2641 6.58527 15.5896 6.91071C15.915 7.23614 15.915 7.76378 15.5896 8.08922L10.5896 13.0892C10.2641 13.4147 9.73651 13.4147 9.41107 13.0892L4.41107 8.08922C4.08563 7.76378 4.08563 7.23614 4.41107 6.9107Z"
                                fill=""
                              />
                            </svg>
                          </>
                        )}
                      </button>

                      {openSubmenu === menuItem.title && (isExpanded || isHovered) && (
                        <div className="translate transform overflow-hidden">
                          <ul className="mb-5.5 mt-4 flex flex-col gap-2.5 pl-6">
                            {menuItem.children.map((child) => (
                              <li key={child.title}>
                                <Link
                                  to={child.path!}
                                  className={`menu-dropdown-item group relative flex items-center gap-3 rounded-lg px-3 py-2.5 font-medium text-theme-sm ${
                                    isActive(child.path!)
                                      ? "menu-dropdown-item-active"
                                      : "menu-dropdown-item-inactive"
                                  }`}
                                >
                                  {child.title}
                                </Link>
                              </li>
                            ))}
                          </ul>
                        </div>
                      )}
                    </div>
                  ) : (
                    <Link
                      to={menuItem.path!}
                      className={`menu-item group relative flex items-center gap-3 rounded-lg px-3 py-2 font-medium text-theme-sm ${
                        isActive(menuItem.path!)
                          ? "menu-item-active"
                          : "menu-item-inactive"
                      }`}
                    >
                      <span
                        className={`menu-item-icon-size ${
                          isActive(menuItem.path!)
                            ? "menu-item-icon-active"
                            : "menu-item-icon-inactive"
                        }`}
                      >
                        {menuItem.icon}
                      </span>
                      {(isExpanded || isHovered) && menuItem.title}
                    </Link>
                  )}
                </li>
              ))}
            </ul>
          </div>
        </nav>
        {/* Sidebar Menu */}
      </div>

      {(isExpanded || isHovered) && <SidebarWidget />}
    </aside>
  );
};

export default AppSidebar;
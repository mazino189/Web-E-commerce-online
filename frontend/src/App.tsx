import { BrowserRouter, Routes, Route } from 'react-router-dom';
import { AuthProvider } from './context/AuthContext';
import { CartProvider } from './context/CartContext';
import ErrorBoundary from './components/ErrorBoundary';
import Navbar from './components/Navbar';
import Footer from './components/Footer';
import Home from './pages/Home';
import ProductDetail from './pages/ProductDetail';
import Cart from './pages/Cart';
import Checkout from './pages/Checkout';
import Login from './pages/Login';
import Register from './pages/Register';
import Orders from './pages/Orders';
import AdminDashboard from './pages/AdminDashboard';
import AdminLayout from './components/AdminLayout';
import AdminProducts from './pages/AdminProducts';
import AdminCategories from './pages/AdminCategories';
import AdminBrands from './pages/AdminBrands';

export default function App() {
    return (
        <BrowserRouter>
            <AuthProvider>
                <CartProvider>
                    <ErrorBoundary>
                        <Routes>
                            {/* Admin Routes */}
                            <Route path="/admin" element={<AdminLayout />}>
                                <Route path="dashboard" element={<AdminDashboard />} />
                                <Route path="products" element={<AdminProducts />} />
                                <Route path="categories" element={<AdminCategories />} />
                                <Route path="brands" element={<AdminBrands />} />
                            </Route>

                            {/* Public/User Routes */}
                            <Route path="/*" element={
                                <div className="min-h-screen flex flex-col bg-canvas">
                                    <Navbar />
                                    <main className="flex-1">
                                        <Routes>
                                            <Route path="/" element={<Home />} />
                                            <Route path="/products/:slug" element={<ProductDetail />} />
                                            <Route path="/cart" element={<Cart />} />
                                            <Route path="/checkout" element={<Checkout />} />
                                            <Route path="/login" element={<Login />} />
                                            <Route path="/orders" element={<Orders />} />
                                            <Route path="/register" element={<Register />} />
                                        </Routes>
                                    </main>
                                    <Footer />
                                </div>
                            } />
                        </Routes>
                    </ErrorBoundary>
                </CartProvider>
            </AuthProvider>
        </BrowserRouter>
    );
}

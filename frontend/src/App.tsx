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

export default function App() {
    return (
        <BrowserRouter>
            <AuthProvider>
                <CartProvider>
                    <div className="min-h-screen flex flex-col bg-canvas">
                        <Navbar />
                        <main className="flex-1">
                            <ErrorBoundary>
                                <Routes>
                                    <Route path="/" element={<Home />} />
                                    <Route path="/products/:slug" element={<ProductDetail />} />
                                    <Route path="/cart" element={<Cart />} />
                                    <Route path="/checkout" element={<Checkout />} />
                                    <Route path="/login" element={<Login />} />
                                    <Route path="/orders" element={<Orders />} />
                                    <Route path="/register" element={<Register />} />
                                </Routes>
                            </ErrorBoundary>
                        </main>
                        <Footer />
                    </div>
                </CartProvider>
            </AuthProvider>
        </BrowserRouter>
    );
}

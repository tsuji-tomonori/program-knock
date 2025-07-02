package com.programknock;

import java.util.*;

public class CountSales {
    
    public static class Sale {
        public final String store;
        public final String paymentMethod;
        public final String product;
        public final int quantity;
        
        public Sale(String store, String paymentMethod, String product, int quantity) {
            this.store = store;
            this.paymentMethod = paymentMethod;
            this.product = product;
            this.quantity = quantity;
        }
    }
    
    public static class SaleKey {
        public final String store;
        public final String paymentMethod;
        public final String product;
        
        public SaleKey(String store, String paymentMethod, String product) {
            this.store = store;
            this.paymentMethod = paymentMethod;
            this.product = product;
        }
        
        @Override
        public boolean equals(Object obj) {
            if (this == obj) return true;
            if (obj == null || getClass() != obj.getClass()) return false;
            SaleKey saleKey = (SaleKey) obj;
            return Objects.equals(store, saleKey.store) &&
                   Objects.equals(paymentMethod, saleKey.paymentMethod) &&
                   Objects.equals(product, saleKey.product);
        }
        
        @Override
        public int hashCode() {
            return Objects.hash(store, paymentMethod, product);
        }
        
        @Override
        public String toString() {
            return "(" + store + ", " + paymentMethod + ", " + product + ")";
        }
    }
    
    public static Map<SaleKey, Integer> countSales(List<Sale> sales) {
        Map<SaleKey, Integer> results = new HashMap<>();
        for (Sale sale : sales) {
            SaleKey key = new SaleKey(sale.store, sale.paymentMethod, sale.product);
            results.merge(key, sale.quantity, Integer::sum);
        }
        return results;
    }
}
package com.programknock;

import java.util.*;
import java.util.stream.Collectors;

public class ProductDeduplication {

    public static class Product {
        public final String name;
        public final int price;

        public Product(String name, int price) {
            this.name = name;
            this.price = price;
        }

        @Override
        public boolean equals(Object obj) {
            if (this == obj) return true;
            if (obj == null || getClass() != obj.getClass()) return false;
            Product product = (Product) obj;
            return price == product.price && Objects.equals(name, product.name);
        }

        @Override
        public int hashCode() {
            return Objects.hash(name, price);
        }

        @Override
        public String toString() {
            return "(" + name + ", " + price + ")";
        }
    }

    public static List<Product> deduplicateProducts(List<Product> products) {
        if (products == null || products.isEmpty()) {
            return new ArrayList<>();
        }

        Map<String, Integer> productMaxPrice = new HashMap<>();
        for (Product product : products) {
            productMaxPrice.merge(product.name, product.price, Integer::max);
        }

        return productMaxPrice.entrySet().stream()
            .map(entry -> new Product(entry.getKey(), entry.getValue()))
            .sorted((p1, p2) -> Integer.compare(p2.price, p1.price))
            .collect(Collectors.toList());
    }
}

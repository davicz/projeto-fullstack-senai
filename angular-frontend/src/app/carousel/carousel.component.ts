import { Component, OnInit, OnDestroy } from '@angular/core';
import { NgFor, NgClass } from '@angular/common';
import { RouterLink } from '@angular/router';

@Component({
  selector: 'app-carousel',
  standalone: true,
  imports: [NgFor, NgClass, RouterLink],
  templateUrl: './carousel.component.html',
  styleUrls: ['./carousel.component.scss']
})
export class CarouselComponent implements OnInit, OnDestroy {

  images = [
    'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1535223289827-42f1e9919769?auto=format&fit=crop&w=1200&q=80'
  ];

  currentSlide = 0;
  private intervalId: any;

  ngOnInit(): void {
    this.intervalId = setInterval(() => {
      this.nextSlide();
    }, 5000);
  }

  ngOnDestroy(): void {
    if (this.intervalId) clearInterval(this.intervalId);
  }

  nextSlide(): void {
    this.currentSlide = (this.currentSlide + 1) % 3;
  }

  goToSlide(index: number): void {
    this.currentSlide = index;
  }
}
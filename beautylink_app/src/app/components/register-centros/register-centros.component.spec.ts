import { ComponentFixture, TestBed } from '@angular/core/testing';

import { RegisterCentrosComponent } from './register-centros.component';

describe('RegisterCentrosComponent', () => {
  let component: RegisterCentrosComponent;
  let fixture: ComponentFixture<RegisterCentrosComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [RegisterCentrosComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(RegisterCentrosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
